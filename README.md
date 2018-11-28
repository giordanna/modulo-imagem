# Módulo Imagem
Pequeno projeto feito em junho de 2018. Deve ser refatorado para uso de Laravel em próximos releases.

## Dependências
- Cropper.js
- SCEditor
- image-map-resizer

## Como instalar
- Baixe ou clone este repositório usando `git clone https://github.com/giordanna/modulo-imagem.git`;
- Crie o banco em seu MySQL ou com o PHPMyAdmin e carregue o arquivo banco.sql para criar as tabelas;
- Todas as instâncias de `mysqli_connect('ip_banco', 'nome_usuario', 'senha_usuario', 'nome_banco')` devem ser modificadas de acordo com as configurações do banco de dados (mais uma vez, esse projeto é antigo, será refatorado quando possível);
- Deve ser criado um admin no MySQL ou com o PHPMyAdmin, e ao inserir na senha deve ser feito o hash com `md5()`;
- Hospede este projeto com o Apache.

## Como utilizar
Como administrador:
- Insira a imagem
- Defina os trechos que deseja tornar clicáveis (Cropper.js)
- Defina o conteúdo dos trechos quando a pessoa clicar (SCEditor)
- Salve. Após isso, será redirecionado para o link da imagem com as áreas selecionadas e seu conteúdo. Isso não será visível para pessoas deslogadas
- As áreas clicáveis são redimensionadas de acordo com o tamanho da tela, sendo assim responsivo (image-map-resizer)

## Dúvidas
 - Caso há alguma dúvida em relação a este repositório, envie para gior.grs@gmail.com