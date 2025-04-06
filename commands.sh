# Criar estrutura de diretórios
mkdir -p docs/brand
mkdir -p docs/emails/templates

# Mover e renomear logos
mv docs/logo-pinksale.png docs/brand/logo-pinksale.png
mv docs/logo-pinksale-texto.png docs/brand/logo-pinksale-texto.png

# Mover e renomear templates de email
mv "docs/EmailClientesdosUsuarios.png" "docs/emails/templates/email-clientes.png"
mv "docs/EmailConsultor.png" "docs/emails/templates/email-consultor.png"
mv "docs/EmailAtivação.png" "docs/emails/templates/email-ativacao.png"
mv "docs/EmailNotificaçõesSistema.png" "docs/emails/templates/email-notificacoes.png"