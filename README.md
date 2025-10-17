# Projeto Açaí e Polpas Amazônia
<h2>
    Projeto de um site conectado a um Banco de Dados <em>MySQL</em> utilizando <em>PHP</em> como ponte entre a <em>Página Web</em> e os <em>Dados</em>
</h2>
<h3>⬇️ Preview do Site</h3>
<a href="https://www.figma.com/design/KG2g0vrnxkWhpYED4uM7DG/Projeto-A%C3%A7a%C3%AD?node-id=0-1&p=f&t=wKqWymchvS68Lj0V-0">
    🖌️ Projeto no <strong>Figma</strong>
</a>

<p>
    Todas as imagens utilizadas na página web estão armazenadas na Nuvem por meio do serviço 
    <a href="https://cloudinary.com/">Cloudinary</a>
</p>
<hr>

<h3>🗄️ API</h3>
<p>
    - Inicialmente, desenvolvida em <strong>PHP</strong>. A API atua como intermediário entre o <strong>Banco de Dados</strong> e a <strong>Página Web.</strong>
</p>

<p>- Gerencia <strong>usuários</strong>, <strong>pedidos</strong> e o <strong>envio de solicitações confirmadas</strong> para uma <em>planilha online</em>.</p>
<p>
    <a href="https://docs.google.com/spreadsheets/d/1xJdM0OgynL5SKLoJ5gxH91abtQ18SY7Xp2dsMVkPvKk/edit?usp=sharing">
        📊 Acesse a <strong>Planilha</strong>
    </a>
</p>
<p>- Os <strong>usuários</strong> são classificados como: </p>
<ul>
    <li><strong>Clientes</strong>: Possuem um <em>pedido ativo</em> ao iniciar uma sessão.</li>
    <li><strong>Administradores</strong>: Possuem <em>foto de perfil</em> e podem <em>alterar</em> dados diretamente no banco.</li>
</ul>
<p>- Há mecanismos de segurança para impedir o acesso a páginas restritas para usuários sem autenticação válida.</p>
<p>- As sessões de cada usuário expiram após cerca de <strong>1 hora</strong>, exigindo nova identificação ao expirar.</p>
<hr>

<h3>🔐 Sistema de Cadastro e Login</h3>
<p> Cada usuário é identificado de forma única por meio de um sistema de <strong>cadastro/login</strong> que inclui: </p>
<ul>
    <li>Nome</li>
    <li>Email*</li>
    <li>Telefone de Contato</li>
    <li>Endereço</li>
    <ul>
        <li>Rua</li>
        <li>Número da Residência</li>
        <li>Bairro</li>
        <li>Cidade</li>
        <li>Estado</li>
        <li>Ponto de Referência</li>
    </ul>
    <li>Foto de Perfil**</li> 
    <li><strong>Senha</strong>***</li>
</ul>
<p>* O email é <em>validado</em> e <em>normalizado</em>(remoção de caracteres invállidos e verificação de domínio).</p>
<p>** Foto de perfil apenas para Administradores</p>
<p>
    *** Senhas Criptografadas com <code>password_hash()</code> em  PHP e armazenadas de forma segura no Banco de Dados.
</p>
<p>
    - O sistema permite <strong>Redefinir Senha</strong> via email, utilizando a biblioteca <strong>PHPmailer</strong> para envio de um token de confirmação.
</p>
<hr>

<h3>⚙️ Ações disponíveis para Administradores</h3>
<ul>
    <li>
        <em>Adicionar</em>, <em>remover</em> ou <em>alterar</em> Dados de um Administradores
    </li>
    <li><em>Remover</em> ou <em>Alterar</em> Dados de um Cliente</li>
    <li><em>Adicionar</em>, <em>Remover</em> ou <em>Editar</em> Dados de um Produto</li>
    <li><em>Adicionar</em>, <em>Remover</em> ou <em>Editar</em> Dados de uma Versão de um Produto</li>
    <li><em>Visualizar</em> Pedidos</li>
    <li><em>Alterar</em> seus próprios dados pessoais</li>
</ul>
<hr>

<h3>🛍️ Página de Produtos</h3>
<p>- É possível <strong>filtrar produtos</strong> por: </p>
<ul> 
    <li><strong>Nome:</strong> Pesquisa, (A–Z), (Z–A)</li> 
    <li><strong>Preço:</strong> Crescente ou Decrescente</li> 
</ul>

<hr>

<h3>📱 Responsividade</h3>
<p> O site é totalmente responsivo e adaptado para dispositivos móveis e telas menores. </p>
<hr>

<h3>⚠️ Observações Importantes</h3>
<p>
    - <strong>OBS</strong>: Por motivos de segurança, as funcionalidades de <strong>alterar imagens</strong> (tanto de Produtos como de Administradores) e de <strong>alterar a planilha online</strong> estão desativadas.
</p>
<p>
    Essas features exigem o uso de <strong>chaves privadas</strong> da API do <em>Cloudinary</em> e do <em>Google Sheets</em>. 
</p>
<p>- Para habilitá-las, é necessário <strong>configurar suas próprias conexões</strong>, conforme indicado na documentação do projeto.</p>
<hr>

<h3>✴️ Créditos e Tecnologias</h3>
<ul>
    <li><strong>Front-End</strong>: HTML5, CSS3, JavaScript</li>
    <li><strong>Back-end</strong>: PHP, MySQL</li>
    <li><strong>Gerenciador de Dependências</strong>: Composer</li>
    <li><strong>Serviços externos</strong>: Cloudinary, Google Sheets, PHPMailer</li>
    <li><strong>Design</strong>: Figma</li>
</ul>

<hr>

<h3>📂 Esquema de Pastas</h3>
    <pre>
        |
        |-- composer                                (Pasta Bibliotecas Utilizadas)
        |-- public                                  (Site Propriamente Dito)
        |   |
        |   |-- account                             (Página do Usuário)
        |   |-- cart                                (Página do Carrinho)
        |   |-- CSS                                 (Folhas de Estilo utilizadas nas páginas)
        |   |-- JS                                  (JavaScript utilizado nas páginas)
        |   |-- mannager                            (Página de Gerenciamento p/ Administradores)
        |   |-- products                            (Página de Produtos)
        |   |-- readMe-images                       (Imagens Utilizadas no README.md)
        |   |-- errorPage.php                       (Página de Erro Geral)
        |   |-- footerHeader.php                    (Código PHP para imprimir o cabeçalho e rodapé de cada página)
        |   |-- GeneralPHP.php                      (Códigos PHP utilizados em mais de uma página)
        |   |-- index.php                           (Página Inicial)
        |
        |-- dbConnection.php                        (Conectar o Banco de Dados com a Página Web)
        |-- DumpProjetoAcai.sql                     (Cópia do Banco de Dados utilizado)   
    </pre>
<hr>

<h3>🖥️ Rodar o Projeto</h3>
<ol type="I">
    <li>
        🪟 Windows
        <ol type='1'>
            <li>Baixe o <a href="https://www.youtube.com/watch?v=0Y9OZ0vc1SU&t=213s">XAMPP</a> e inicie um Servidor Local.</li>
            <li>
                Ative os módulos <strong>Apache</strong> e <strong>MySQL</strong> no painel do <strong>XAMPP</strong>.
            </li>
            <li>
                Crie uma conexão no <a href="https://www.youtube.com/watch?v=a5ul8o76Hqw&t=13s">MySQL WorkBench</a> ou outro aplicativo gerenciador de Banco de Dados.
            </li>
            <li>
                Abra o Arquivo <code>"DumpProjetoAcai.sql"</code>, copie e execute o código dentro de sua conexão clicando no símbolo demonstrado abaixo para importar o banco de dados.
            </li>
            <img src="public/readMe-images/dump.jpg" alt="Dump DataBase Preview"></img>
            <li>
                Verifique se o banco foi adicionado acessando acessando no Navegador <code>"localhost/phpmyadmin"</code>. <br>
                Se a tabela <strong>acai_admin</strong> aparecer na barra lateral, está tudo certo.
            </li>
            <img src="public/readMe-images/phpmyadmin.jpg" alt="PHPMyAdmin Preview"></img>
            <li>
                Mova a Pasta do Projeto para: 
                <pre>C:\xampp\htdocs</pre>
            </li>
            <li>
                No terminal (CMD ou PowerShell), acesse a pasta do Projeto e instale as dependências do Composer:
                <pre>
cd composer
composer install
composer require cloudinary/cloudinary_php
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
composer require google/apiclient:^2.0
                </pre>
            </li>
            <img src="public/readMe-images/composer.jpg" alt="Composer Archive Preview"></img>
            <li>Crie um <a href="https://www.youtube.com/watch?v=k_PB4ORz2r0">Projeto no Google Cloud</a>.</li>
            <li>Ative a API do Google Sheets.</li>
            <li>
                Crie uma conta de serviço e baixe o arquivo <code>credenciais.json</code>.<br>
                Cole-o na raiz do projeto:
                <pre>C:\xampp\htdocs\Projeto_Acai2.0</pre>
            </li>
            <li>
                No arquivo <code>cart.php</code>, altere o caminho em:
                <pre>$config->setAuthConfig('caminho')</pre>
                para o local correto do seu credenciais.json.
            </li>
            <img src="public/readMe-images/googleAPI.jpg" alt="Google API Code Preview"></img>
            <li>
                Altere o valor de <strong>$spreadsheetId</strong> conforme o ID da sua planilha(ID se encontra onde está escrito "IDAQUI" na imagem abaixo):
            </li>
            <img src="public/readMe-images/spreadSheetId.jpg" alt="SpreadSheet ID"></img>
            <img src="public/readMe-images/googleAPI2.jpg" alt="SpreadSheet ID Location"></img>
            <li>Compartilhe a planilha com o e-mail da conta de serviço.</li>
            <li>Crie uma Conta no <a href="https://cloudinary.com">Cloudinary</a></li>
            <li>
                Copie sua <strong>API Key</strong>(veja o <a href="https://youtu.be/ZSIt6nCkqNc?si=zzNuC-CHRqCzuVdX&t=34">tutorial aqui</a>) e cole no arquivo <code>.env</code> dentro da pasta <code>composer</code>.
            </li>
            <li>
                Por fim, acesse o site no Navegador:
                <pre>http://localhost/Projeto_Acai2.0/public</pre>
            </li>
        </ol>
    </li>
    <li>
        🐧 Linux
        <ol type='1'>
            <li>Instale o <a href="https://www.youtube.com/watch?v=XoKUkdmfTZQ">XAMPP</a></li>
            <li>Ative os módulos <strong>Apache Web Server</strong> e <strong>MySQL Database</strong>:
                <ul>
                    <li>
                        Pelo Terminal: 
                        <pre>sudo /opt/lampp/lampp start</pre>
                    </li>
                    <li>
                        Ou pela Interface Gráfica: 
                        <pre>cd /opt/lamppsudo 
./manager-linux-x64.run</pre>
                    </li>
                </ul>
            </li>
            <li>
                Crie uma conexão no <a href="https://youtu.be/Uuw4KPiVATc?si=8L49cPxz9CTX09NE">MySQL WorkBench</a> ou outro aplicativo gerenciador de Banco de Dados.
            </li>
            <li>
                Importe o banco de dados com <code>"DumpProjetoAcai.sql"</code> (igual ao passo no Windows).
            </li>
            <li>
                Verifique o banco em: 
                <pre>http://localhost/phpmyadmin</pre>
            </li>
            <img src="public/readMe-images/dump.jpg" alt="Dump DataBase Preview"></img>
            <li>
                Mova a Pasta do Projeto para: 
                <pre>/opt/lampp/htdocs</pre>
            </li>
            <li>
                No terminal, acesse a pasta do Projeto e instale as dependências do Composer:
                <pre>
cd composer
composer install
composer require cloudinary/cloudinary_php
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
composer require google/apiclient:^2.0
                </pre>
            </li>
            <img src="public/readMe-images/composer.jpg" alt="Composer Archive Preview"></img>
            <li>
            Crie o projeto no Google Cloud e ative a API do Sheets (mesmos passos do Windows).
            <li>
                Crie uma conta de serviço e baixe o arquivo <code>credenciais.json</code>.<br>
                Cole-o na raiz do projeto:
                <pre>/opt/lampp/htdocs/Projeto_Acai2.0</pre>
            </li>
            <li>
                No arquivo <code>cart.php</code>, altere o caminho em:
                <pre>$config->setAuthConfig('caminho')</pre>
                para o local correto do seu credenciais.json.
            </li>
            <img src="public/readMe-images/googleAPI.jpg" alt="Google API Code Preview"></img>
            <li>
                Altere o valor de <strong>$spreadsheetId</strong> conforme o ID da sua planilha(ID se encontra onde está escrito "IDAQUI" na imagem abaixo):
            </li>
            <img src="public/readMe-images/spreadSheetId.jpg" alt="SpreadSheet ID"></img>
            <img src="public/readMe-images/googleAPI2.jpg" alt="SpreadSheet ID Location"></img>
            <li>Compartilhe a planilha com o e-mail da conta de serviço.</li>
            <li>Crie uma Conta no <a href="https://cloudinary.com">Cloudinary</a></li>
            <li>
                Copie sua <strong>API Key</strong>(veja o <a href="https://youtu.be/ZSIt6nCkqNc?si=zzNuC-CHRqCzuVdX&t=34">tutorial aqui</a>) e cole no arquivo <code>.env</code> dentro da pasta <code>composer</code>.
            </li>
            <li>
                Por fim, acesse o site no Navegador:
                <pre>http://localhost/Projeto_Acai2.0/public</pre>
            </li>
        </ol>
    </li>
</ol>
<p>
    <strong>📺 OBS: Os videos citados acima estão aqui apenas para facilitar a retirada de dúvidas em relação a como rodar o projeto.</strong>
</p>
<hr>

<h3>📋 Para fazer: </h3>
<ul>
    <li>Verificação de Existencia de Email e Número de Telefone</li>
    <li>API de Pagamentos</li>
</ul>
