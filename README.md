# Projeto Açaí e Polpas Amazônia
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

<h3>🗄️ API</h3>
<p>
    - Inicialmente, projetada utilizando a linguagem <strong>PHP</strong> como ponte entre o <strong>Banco de Dados</strong>(BackEnd) e a <strong>Página Web</strong>(FrontEnd)
</p>
<p>- Gerenciamento de Usuários, seus Pedidos e envio para uma Planilha das solicitações que forem Confirmados</p>
<p>- Possui soluções para usuários que tentarem acessar Páginas Bloqueadas sem cadastro efetuado</p>
<p>- As sessões de cada usuário duram cerca de 1 hora. Após, será necessário realizar novamente a identificação</p>
<p>- Para diferenciar cada usuário que utilize o site, há um sistema de Cadastro/Login, incluindo: </p>
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
        |-- composer                                (Pasta Bibliotecas Utilizadas)
        |-- public                                  (Site Propriamente Dito)
        |   |
        |   |-- account                             (Página do Usuário)
        |   |-- cart                                (Página do Carrinho)
        |   |-- products                            (Página de Produtos)
        |   |-- readMe-images                       (Imagens Utilizadas no ReadMe.md)
        |   |-- JS                                  (JavaScript utilizado nas páginas)
        |   |-- CSS                                 (Folhas de Estilo utilizadas nas páginas)
        |   |-- errorPage.php                       (Página de Erro Geral)
        |   |-- GeneralPHP.php                      (Códigos PHP utilizados em mais de uma página)
        |   |-- index.php                           (Página Inicial)
        |
        |-- dbConnection.php                        (Conectar o Banco de Dados com o FrontEnd)
        |-- dumpProjeto_acai.sql                    (Cópia do Banco de Dados utilizado)   
    </pre>
<hr>

<h3>🖥️ Rodar o projeto</h3>
<ol>
    <li>
        🪟 Windows
        <ul>
            <li>Baixe o <a href="https://www.youtube.com/watch?v=0Y9OZ0vc1SU&t=213s">XAMPP</a></li>
            <li>Ative os módulos <strong>Apache</strong> e <strong>MySQL</strong> dentro do <strong>XAMPP</strong></li>
            <li>Baixe o <a href="https://www.youtube.com/watch?v=a5ul8o76Hqw&t=13s">MySQL WorkBench</a> ou outro aplicativo gerenciador de Banco de Dados</li>
            <li>Abra o arquivo "DumpProjeto_acai.sql", copie o código dentro dele e Clone o Banco de Dados dentro do MySQL (Dump)</li>
            <li>
                Adicione o Banco de Dados ao seu Servidor Local clicando no símbolo demonstrado abaixo <br> 
                <img src="public/readMe-images/dump.png" alt="Dump DataBase Preview"></img>
            </li>
            <li>
                Para verificar se o Banco de Dados foi realmente adicionado, digite no navegador "localhost/phpmyadmin". <br>
                Se a relação <strong>"acai_admin"</strong> existir na aba esquerda da tela ➡️ Adicionado com Sucesso <br>
                <img src="public/readMe-images/phpmyadmin.png" alt="PHPMyAdmin Preview"></img>
            </li>
            <li>Adicione a Pasta do Projeto ao Diretório <strong>"htdocs"</strong> dentro de <strong>Xampp</strong> <pre>(C:\xampp\htdocs)</pre></li>
            <li>
                Para acessar o site, Digite no Navegador <pre>http://localhost/siteAcai-2.0/public</pre>
                <img src="public/readMe-images/local.png" alt="local Preview"></img>
            </li>
        </ul>
    </li>
    <li>
        🐧 Linux
        <ul>
            <li>Instale o <a href="https://youtu.be/Uuw4KPiVATc?si=8L49cPxz9CTX09NE&t=211">MySQL WorkBench</a> ou outro aplicativo gerenciador de Banco de Dados</li>
            <li>Instale o <a href="https://www.youtube.com/watch?v=XoKUkdmfTZQ">XAMPP</a></li>
            <li>Ative os módulos <strong>Apache Web Server</strong> e <strong>MySQL Database</strong> de 2 maneiras: </li>
                <ol>
                    <li>Ativando os módulos pelo terminal <br> <pre>sudo /opt/lampp/lampp start</pre></li>
                    <li>Ativando pela interface gráfica <br> <pre>cd /opt/lampp</pre> <pre>sudo ./manager-linux-x64.run</pre></li>
                </ol>
            <li>
                Adicione o Banco de Dados ao seu Servidor Local clicando no símbolo demonstrado abaixo <br> 
                <img src="public/readMe-images/dump.png" alt="Dump DataBase Preview"></img>
            </li>
            <li>
                Para verificar se o Banco de Dados foi realmente adicionado, digite no navegador "localhost/phpmyadmin" ou "127.0.0.1/phpmyadmin". <br>
                Se a relação <strong>"acai_admin"</strong> existir na aba esquerda da tela ➡️ Adicionado com Sucesso <br>
                <img src="public/readMe-images/phpmyadmin.png" alt="PHPMyAdmin Preview"></img>
            </li>
            <li>Adicione a Pasta do Projeto ao Diretório <strong>"htdocs"</strong> dentro de <strong>Xampp</strong> <pre>(/opt/lampp/htdocs)</pre></li>
            <li>
                Para acessar o site, Digite no Navegador <pre>http://localhost/siteAcai-2.0/public</pre>
                <img src="public/readMe-images/local.png" alt="local Preview"></img>
            </li>
        </ul>
    </li>

</ol>

<strong>
    <p>OBS: Os videos citados acima estão aqui apenas para facilitar a retirada de dúvidas em relação a como rodar o projeto</p>
</strong>
<hr>


<h3>📋 Para fazer: </h3>
<ul>
    <li>✅ Link Página de Produtos com Página do Carrinho </li>
    <li>✅ PHP Página do Carrinho + Link Planilha Excel/Google Planilhas (concluir Pedido)</li>
    <li>‼️Filtros na Página de Produtos</li>
    <li>✅ Pesquisas na Página de Produtos</li>
    <li>✅ Página de Mudança de Credenciais</li>
    <li>✅ Página especial para email</li>
    <li>✅ Página especial para Senha</li>
    <li>✅ Mudanças na Página de logout (enviar p/ pagina certa)</li>
    <li>✅ Página "Esqueceu a Senha"</li>
    <li>Verificação de Existencia de Email e Número de Telefone</li>
    <li>API de Pagamentos</li>
</ul>