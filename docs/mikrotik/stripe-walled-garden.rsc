# =====================================================================
# Walled-garden para Stripe en hotspot MikroTik (RouterOS 6.x)
# Reemplaza el script "stripe-wg-update" y la lista Stripe-Bypass.
#
# Por que: las IPs de docs.stripe.com/ips son de WEBHOOKS (trafico
# saliente de Stripe hacia tu servidor), NO las IPs del CDN que sirve
# js.stripe.com. El CDN (Fastly) rota IPs; congelarlas en address-list
# y DNS static rompe el checkout de forma intermitente.
#
# Solucion: /ip hotspot walled-garden ip con dst-host. RouterOS
# resuelve el dominio y mantiene las entradas dinamicas siguiendo el
# TTL del DNS. Requiere que los clientes usen el DNS del router
# (de ahi el redirect de puerto 53).
# =====================================================================

# ---- 1) Eliminar el mecanismo anterior ----
/system scheduler remove [find name="stripe-wg-refresh"]
/system script remove [find name="stripe-wg-update"]
/ip hotspot walled-garden ip remove [find comment~"STRIPE_WG_AUTO"]
/ip firewall filter remove [find comment~"STRIPE_AUTO"]
/ip dns static remove [find comment~"STRIPE_DNS_AUTO"]
/ip firewall filter remove [find comment~"Stripe-Bypass"]
/ip firewall nat remove [find comment~"Stripe-Bypass"]
/ip firewall address-list remove [find list="Stripe-Bypass"]
/ip dns cache flush

# ---- 2) Walled-garden por dominio ----
/ip hotspot walled-garden ip
add action=accept dst-host=js.stripe.com comment="Stripe"
add action=accept dst-host=api.stripe.com comment="Stripe"
add action=accept dst-host=m.stripe.com comment="Stripe"
add action=accept dst-host=m.stripe.network comment="Stripe"
add action=accept dst-host=r.stripe.com comment="Stripe"
add action=accept dst-host=q.stripe.com comment="Stripe"
add action=accept dst-host=b.stripecdn.com comment="Stripe CDN"
add action=accept dst-host=errors.stripe.com comment="Stripe"
add action=accept dst-host=test.interservicetelecomunicaciones.com comment="Portal"

# ---- 3) Forzar DNS del router en la red hotspot ----
/ip firewall nat
add chain=dstnat action=redirect to-ports=53 protocol=udp dst-port=53 \
    src-address=10.5.50.0/24 comment="Forzar DNS local"
add chain=dstnat action=redirect to-ports=53 protocol=tcp dst-port=53 \
    src-address=10.5.50.0/24 comment="Forzar DNS local"

# ---- 4) Seguridad / reduccion de carga ----
# API solo accesible desde el VPS (ajustar IP si cambia)
/ip service set api address=45.174.92.234/32
# Cambiar la contrasena antes de ejecutar:
# /ip hotspot user set [find name="admin"] password="CAMBIAR"
/ip firewall filter
add chain=input action=accept connection-state=established,related place-before=0
# Verificar acceso remoto por VPN antes de activar el drop de WAN:
add chain=input action=drop in-interface=ether1 comment="Drop WAN input"
