# Projeto Açaí e Polpas Amazônia
<h2>
    Project  based on a website connected to a <em>MySQL</em> Database using <em>PHP</em> as an intermediate layer between the <em>Web Page</em> and the <em>Data</em>.
</h2>

<h3>⬇️ Website Preview</h3>
<a href="https://www.figma.com/design/KG2g0vrnxkWhpYED4uM7DG/Projeto-A%C3%A7a%C3%AD?node-id=0-1&p=f&t=wKqWymchvS68Lj0V-0">
    🖌️ <strong>Figma</strong> Project
</a>

<p>
    All images used in this project are stored in the <em>cloud</em> using the <a href="https://cloudinary.com/">Cloudinary</a> service.
</p>

<h3>🗄️ API</h3>
<p>
    Developed in <strong>PHP</strong>. The API acts as an intermediate layer between the <strong>Database</strong> and the <strong>Web Page</strong>.
</p>
<p>Its manages <strong>users</strong> and <strong>orders</strong> integrated with <em>online spreadsheets</em>.</p>
<p>
    <a href="https://docs.google.com/spreadsheets/d/1xJdM0OgynL5SKLoJ5gxH91abtQ18SY7Xp2dsMVkPvKk/edit?usp=sharing">
        📊 Access the <strong>Spreadsheet</strong> here.
    </a>
</p>
<p><strong>Users</strong> are classified as:</p>
<ul>
    <li><strong>Client</strong>:  Has an <em>activated order</em> once autenticated.</li>
    <li><strong>Administrator</strong>: Has a <em>profile picture</em> and can <em>modify</em> database data.</li>
</ul>
<p>Secure mechanisms prevent unauthorized users from accessing restricted pages.</p>
<p>User sessions last for about <strong>1 hour</strong>, requiring re-authentication after expiration.</p>
<hr>


<h3>🔐 Sign-in and Sign-up System</h3>
<p>Each user identified through a <strong>sign-in/ sign-up system</strong>, including:</p>
<ul>
    <li>Name</li>
    <li>Email*</li>
    <li>Contact Phone Number</li>
    <li>Address</li>
    <ul>
        <li>Street</li>
        <li>Local Number</li>
        <li>District</li>
        <li>City</li>
        <li>State</li>
        <li>Reference Point</li>
    </ul>
    <li>Profile Picture**</li> 
    <li><strong>Password</strong>***</li>
</ul>
<p>* Email is <em>validated</em> and <em>normalized</em> (invalid characters are removed and the domain is verified).</p>
<p>** Profile Pictureis only available for Administrators.</p>
<p>*** Passwords are encrypted with <code>password_hash()</code> in PHP and securely stored in the database.</p>
<p>The system allows users to <strong>reset their password</strong> via email, using the <strong>PHPmailer</strong> Library to send a verification token.</p>
<hr>

<h3>⚙️ Available Admin Actions</h3>
<ul>
    <li>Perform CRUD operations on <strong>Admin Data</strong>.</li>
    <li>Remove or modify <strong>Client Data</strong>.</li>
    <li>Perform CRUD operations on <strong>Product</strong> and <strong>Product Versions</strong>.</li>
    <li>View <strong>Orders</strong>.</li>
    <li>Modify personal data.</li>
</ul>
<hr>

<h3>🛍️ Product Page</h3>
<p>Products can be filtered by:</p>
<ul> 
    <li><strong>Name:</strong> Search, (A–Z), (Z–A)</li> 
    <li><strong>Price:</strong> Ascending or Descending</li> 
</ul>
<hr>

<h3>📱 Responsiveness</h3>
<p>The Website is fully responsive for mobile devices and different screens resolutions.</p>
<hr>

<h3>⚠️ Important Notes</h3>
<p>
    For security reasons, <em>image upload(product version or admin)</em> and <em>spreadsheet editing</em> features are disabled by default.
</p>
<p>
    Those features require <em>private API tokens</em> from <strong>Cloudinary</strong> and <strong>Google Sheets</strong>.
</p>
<p>To enable them, you must <strong>setup your own connections</strong>, as described in the <em>"How to Run"</em> section.</p>
<hr>

<h3>✴️ Technologies and Credits</h3>
<ul>
    <li><strong>Front-End</strong>: HTML5, CSS3, JavaScript</li>
    <li><strong>Back-end</strong>: PHP, MySQL</li>
    <li><strong>Dependency Manager</strong>: Composer</li>
    <li><strong>External Services</strong>: Cloudinary, Google Sheets, PHPMailer</li>
    <li><strong>Design</strong>: Figma</li>
</ul>
<hr>

<h3>📂 Directory Scheme</h3>
<pre>
    |
    |-- composer                                (Used Libraries)
    |-- public                                  (The Website)
    |   |
    |   |-- account                             (User Page)
    |   |-- cart                                (Cart Page)
    |   |-- CSS                                 (Stylesheets)
    |   |-- JS                                  (JavaScript files)
    |   |-- manager                             (Admin Manager Page)
    |   |-- products                            (Products Page)
    |   |-- readMe-images                       (images used in README.md)
    |   |-- errorPage.php                       (Generic Error Page)
    |   |-- footerHeader.php                    (PHP file for header/footer reuse)
    |   |-- GeneralPHP.php                      (PHP code used in multiple pages)
    |   |-- index.php                           (Home Page)
    |
    |-- dbConnection.php                        (Connect Database with the Web Page)
    |-- DumpProjetoAcai.sql                     (Database Dump)
</pre>
<hr>


<h3>🖥️ How to Run</h3>
<ol type="I">
    <li>
        🪟 Windows
        <ol type='1'>
            <li>Download <a href="https://www.youtube.com/watch?v=0Y9OZ0vc1SU&t=213s">XAMPP</a> and start a local server.</li>
            <li>Activate the <strong>Apache</strong> and <strong>MySQL</strong> modules on the XAMPP panel.</li>
            <li>Create a connection in <a href="https://www.youtube.com/watch?v=a5ul8o76Hqw&t=13s">MySQL Workbench</a> or another database management tool.</li>
            <li>Open the <code>DumpProjetoAcai.sql</code> file, copy the code, and run it in your connection to import the database.</li>
            <img src="public/readMe-images/dump.jpg" alt="Dump Database Preview"></img>
            <li>Verify the database import by visiting <code>localhost/phpmyadmin</code>. If you see the <strong>acai_admin</strong> table, everything is ready.</li>
            <img src="public/readMe-images/phpmyadmin.jpg" alt="PHPMyAdmin Preview"></img>
            <li>Move the project directory to:
                <pre>C:\xampp\htdocs</pre>
            </li>
            <li>In the terminal (CMD or PowerShell), navigate to the project directory and install the Composer dependencies:
                <pre>
cd composer
composer install
composer require cloudinary/cloudinary_php
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
composer require google/apiclient:^2.0
                </pre>
            </li>
            <img src="public/readMe-images/composer.jpg" alt="Composer Installation Preview"></img>
            <li>Create a <a href="https://www.youtube.com/watch?v=k_PB4ORz2r0">Google Cloud Project</a> and enable the Google Sheets API.</li>
            <li>Create a service account and download the <code>credentials.json</code> file. Place it in:
                <pre>C:\xampp\htdocs\Projeto_Acai2.0</pre>
            </li>
            <li>In the <code>cart.php</code> file, update:
                <pre>$config->setAuthConfig('path')</pre>
                to the correct <code>credentials.json</code> path.
            </li>
            <img src="public/readMe-images/googleAPI.jpg" alt="Google API Code Preview"></img>
            <li>Replace the <strong>$spreadsheetId</strong> value with your spreadsheet ID (as shown below):</li>
            <img src="public/readMe-images/spreadSheetId.jpg" alt="Spreadsheet ID Example"></img>
            <img src="public/readMe-images/googleAPI2.jpg" alt="Spreadsheet ID Location"></img>
            <li>Share the spreadsheet with your service account email.</li>
            <li>Create an account on <a href="https://cloudinary.com">Cloudinary</a>.</li>
            <li>Copy your <strong>API Key</strong> (<a href="https://youtu.be/ZSIt6nCkqNc?si=zzNuC-CHRqCzuVdX&t=34">tutorial here</a>) and paste it in the <code>.env</code> file inside the <code>composer</code> directory.</li>
            <li>Access the website:
                <pre>http://localhost/Projeto_Acai2.0/public</pre>
            </li>
        </ol>
    </li>
    <li>
        🐧 Linux
        <ol type='1'>
            <li>Install <a href="https://www.youtube.com/watch?v=XoKUkdmfTZQ">XAMPP</a>.</li>
            <li>Activate the <strong>Apache</strong> and <strong>MySQL</strong> modules:
                <ul>
                    <li>Terminal:
                        <pre>sudo /opt/lampp/lampp start</pre>
                    </li>
                    <li>Or via GUI:
                        <pre>cd /opt/lampp
sudo ./manager-linux-x64.run</pre>
                    </li>
                </ul>
            </li>
            <li>Create a connection in <a href="https://www.youtube.com/watch?v=a5ul8o76Hqw&t=13s">MySQL Workbench</a> or another database manager.</li>
            <li>Import the <code>DumpProjetoAcai.sql</code> file (same step as Windows).</li>
            <li>Verify the database at:
                <pre>http://localhost/phpmyadmin</pre>
            </li>
            <img src="public/readMe-images/dump.jpg" alt="Dump Database Preview"></img>
            <li>Move the project directory to:
                <pre>/opt/lampp/htdocs</pre>
            </li>
            <li>In the terminal, navigate to the project directory and install the Composer dependencies:
                <pre>
cd composer
composer install
composer require cloudinary/cloudinary_php
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
composer require google/apiclient:^2.0
                </pre>
            </li>
            <img src="public/readMe-images/composer.jpg" alt="Composer Installation Preview"></img>
            <li>Create the project on Google Cloud and enable the Sheets API (same as Windows).</li>
            <li>Download the <code>credentials.json</code> file and place it in:
               <pre>/opt/lampp/htdocs/Projeto_Acai2.0</pre>
            </li>
            <li>In the <code>cart.php</code> file, update:
                <pre>$config->setAuthConfig('path')</pre>
                to the correct path.
            </li>
            <img src="public/readMe-images/googleAPI.jpg" alt="Google API Code Preview"></img>
            <li>Replace <strong>$spreadsheetId</strong> with your spreadsheet ID (see below):</li>
            <img src="public/readMe-images/spreadSheetId.jpg" alt="Spreadsheet ID Example"></img>
            <img src="public/readMe-images/googleAPI2.jpg" alt="Spreadsheet ID Location"></img>
            <li>Share the spreadsheet with your service account email.</li>
            <li>Create an account on <a href="https://cloudinary.com">Cloudinary</a>.</li>
            <li>Copy your <strong>API Key</strong> and paste it in the <code>.env</code> file inside the <code>composer</code> directory.</li>
            <li>Access the website:
                <pre>http://localhost/Projeto_Acai2.0/public</pre>
            </li>
        </ol>
    </li>
</ol>

<hr>

<h3>📋 TO DO: </h3>
<ul>
    <li>Verify the email and phone number existence.</li>
    <li>Integrate a payment API.</li>
</ul>