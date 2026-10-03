User-agent: *
Allow: /
Disallow: /carrinho
Disallow: /lista-de-desejos
Disallow: /lista-desejos
Disallow: /finalizacao-de-compra
Disallow: /checkout
Disallow: /debug/
Disallow: /refresh-csrf-token
Disallow: /*?orderby=
Disallow: /*?min_price=
Disallow: /*?max_price=
Disallow: /*?s=
Disallow: /*?colors=

User-agent: Googlebot
Allow: /
Disallow: /carrinho
Disallow: /finalizacao-de-compra
Disallow: /debug/

User-agent: Googlebot-Image
Allow: /

User-agent: Storebot-Google
Allow: /

Sitemap: {{ $sitemap }}
