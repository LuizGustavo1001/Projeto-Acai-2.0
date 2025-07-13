# Projeto Açaí Amazônia Ipatinga
<h2>Projeto de um site conectado a um Banco de Dados <em>MySQL</em> utilizando <em>PHP</em> como ponte entre <em>FrontEnd</em> e <em>BackEnd</em></h2>
<h3>⬇️ Preview do Site</h3>

<a href="https://www.figma.com/design/KG2g0vrnxkWhpYED4uM7DG/Projeto-A%C3%A7a%C3%AD?node-id=0-1&p=f&t=wKqWymchvS68Lj0V-0">
    Projeto no <strong>Figma</strong>
</a>

<p>Sistema de Gerenciamento de Vendas para Clientes de produtos selecionados, adicionados ao carrinho e confirmados</p>
<p>
    Todas as imagens utilizadas na página web foram adicionadas à nuvem por meio do serviço 
    <a href="https://cloudinary.com/">Cloudinary</a>
</p>

<hr>

<h3>🗄️ API <small>(em desenvolvimento)</small></h3>
<p>
    - Inicialmente, projetada utilizando a linguagem <strong>PHP</strong> para conectar o <strong>Banco de Dados</strong>(BackEnd) com uma <strong>Página Web</strong>(FrontEnd)
</p>
<p>- Gerenciamento de Usuários, seus Pedidos e envio para uma Planilha das solicitações que forem Confirmados</p>
<p>- Possui soluções para usuários que tentarem acessar Páginas Bloqueadas sem cadastro efetuado</p>
<p>- As sessões de cada usuário duram cerca de 1 hora. Após, será necessário realizar novamente a identificação</p>
<p>- Para diferenciar cada usuário que utilizar o site, existe um sistema de Cadastro/Login, incluindo: </p>
<ul>
    <li>Nome</li>
    <li>Email</li>
    <li>Telefone de Contato</li>
    <li>Endereço</li>
    <ul>
        <li>Rua</li>
        <li>Número da Residência</li>
        <li>Bairro</li>
        <li>Cidade</li>
        <li>Ponto de Referência</li>
    </ul>
    <li><strong>Senha</strong>*</li>
</ul>
<p>- Email recebido de forma limpa (removendo caracteres indesejados)</p>
<p>
    - 🔐 Senhas Criptografadas por meio da função <strong><em>password_hash()</em></strong> dentro do PHP e salvas no Banco de Dados já criptografadas
</p>

<p>- 📱  Responsividade em dispositivos portáteis / menores</p>

<hr>

<h3>📂 Esquema de Pastas</h3>
    <pre>
        |
        |-- account                             (Página do Usuário)
        |-- cart                                (Página do Carrinho)
        |-- products                            (Página de Produtos)
        |-- readMe-images                       (Imagens Utilizadas no ReadMe.md)
        |-- scripts                             (JavaScript utilizado nas páginas)
        |-- styles                              (Folhas de Estilo utilizadas nas páginas)
        |-- dbConnection.php                    (Conectar o Banco de Dados com o FrontEnd)
        |-- DumpProjeto_acai.sql                (Cópia do Banco de Dados utilizado)
        |-- errorPage.php                       (Página de Erro Geral)
        |-- GeneralPHP.php                      (Códigos PHP utilizados em mais de uma página)
        |-- index.php                           (Página Inicial)
        |-- logout.php                          (Sistema para deslogar um usuário)
    </pre>
<hr>

<h3>🖥️ Rodar o projeto</h3>
<ol>
    <li>Baixe o <a href="https://www.youtube.com/watch?v=0Y9OZ0vc1SU&t=213s">XAMPP</a></li>
    <li>Ative os módulos <strong>Apache</strong> e <strong>MySQL dentro do XAMPP</strong></li>
    <li>Baixe o <a href="https://www.youtube.com/watch?v=a5ul8o76Hqw&t=13s">MySQLWorkBench</a></li>
    <li>Abra o arquivo "DumpProjeto_acai.sql", copie o código dentro dele e Clone o Banco de Dados dentro do MySQL (Dump)</li>
    <li>
        Adicione o Banco de Dados ao seu Servidor Local clicando no símbolo demonstrado abaixo <br> 
        <img src="readMe-images/dump.png" alt="Dump DataBase"></img>
    </li>
    <li>
        Para verificar se o Banco de Dados foi realmente adicionado digite no navegador "localhost/phpmyadmin", se a relação "projeto_acai" existir
        na aba esquerda da tela o Banco de Dados foi adicionado com sucesso <img src="readMe-images/phpmyadmin.png" alt="PHPMyAdmin Preview"></img>
    </li>
    <li>Adicione a Pasta do projeto a pasta "htdocs" dentro de xampp (C:\xampp\htdocs)</li>
    <li>
        Digite no Navegador "http://localhost/siteAcai-2.0"
        <img src="readMe-images/local.png" alt=""></img>
    </li>
</ol>

<hr>


<h3>📋 Para fazer: </h3>
<ul>
    <li>✅ Link Página de Produtos com Página do Carrinho </li>
    <li>✅ PHP Página do Carrinho + Link Planilha Excel/Google Planilhas (concluir Pedido)</li>
    <li>Filtros na Página de Produtos</li>
    <li>✅ Pesquisas na Página de Produtos</li>
    <li>✅ Página de Mudança de Credenciais</li>
    <li>Página especial para email</li>
    <li>Senha(enviar email de confirmação)</li>
    <li>✅ Mudanças na Página de logout (enviar p/ pagina certa)</li>
    <li>✅ Página "Esqueceu a Senha"</li>
    <li>Verificação de Existencia de Email e Número de Telefone</li>
</ul>