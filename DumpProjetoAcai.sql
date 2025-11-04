
--
-- Despejando dados para a tabela `admin_data`
--

INSERT INTO `admin_data` (`idAdmin`, `adminPicture`) VALUES
(1, 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758992688/Users-Pictures/adminPic001.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `attribute_change`
--

CREATE TABLE `attribute_change` (
  `idChange` int(11) NOT NULL,
  `tableName` varchar(30) NOT NULL,
  `idAttribute` int(11) NOT NULL,
  `objectChanged` varchar(40) NOT NULL,
  `oldValue` varchar(250) DEFAULT NULL,
  `newValue` varchar(250) DEFAULT NULL,
  `typeChange` enum('Remover','Modificar','Adicionar') NOT NULL DEFAULT 'Modificar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `change_data`
--

CREATE TABLE `change_data` (
  `idChange` int(11) NOT NULL,
  `changeDate` date NOT NULL,
  `changeHour` time NOT NULL,
  `idAdmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `client_data`
--

CREATE TABLE `client_data` (
  `idClient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `client_data`
--

INSERT INTO `client_data` (`idClient`) VALUES
(2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `order_data`
--

CREATE TABLE `order_data` (
  `idOrder` int(11) NOT NULL,
  `idClient` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderHour` time NOT NULL,
  `orderStatus` enum('Pendente','Finalizado') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_data`
--

CREATE TABLE `product_data` (
  `idProduct` int(11) NOT NULL,
  `printName` varchar(60) NOT NULL,
  `altName` varchar(40) NOT NULL,
  `brandProduct` varchar(40) DEFAULT 'Outra Marca',
  `typeProduct` enum('Outro','Adicional','Creme','') DEFAULT 'Outro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `product_data`
--

INSERT INTO `product_data` (`idProduct`, `printName`, `altName`, `brandProduct`, `typeProduct`) VALUES
(1, 'Caixa de Açaí', 'CaixaAcai', 'Açaí e Polpas Amazônia', 'Creme'),
(2, 'Colheres p/ Açaí e Sorvete', 'colheres', 'Plastjet', 'Outro'),
(3, 'Cremes Frutados', 'cremesFrutados', 'Energia Natural', 'Creme'),
(4, 'Açaí Zero', 'acaiZero', 'Outra Marca', 'Creme'),
(5, 'Açaí c/Ninho', 'acaiNinho', 'Outra Marca', 'Creme'),
(6, 'Morango Congelado', 'morango', 'Outra Marca', 'Outro'),
(7, 'Leite em Pó', 'leiteEmPo', 'Outra Marca', 'Adicional'),
(8, 'Granola Tia Sônia', 'granolaTiaSonia', 'Tia Sônia', 'Adicional'),
(9, 'Granola Tradicional', 'granolaTradicional', 'Outra Marca', 'Adicional'),
(10, 'Caixa de Paçoca', 'caixaPacoca', 'Unidoces', 'Adicional'),
(11, 'Farofa de Paçoca', 'farofaPacoca', 'Balsamo', 'Adicional'),
(12, 'Amendoim Triturado', 'amendoimTriturado', 'Balsamo', 'Adicional'),
(13, 'Ovomaltine', 'ovomaltine', 'Ovomaltine', 'Adicional'),
(14, 'Gotas de Chocolate', 'gotasChocolate', 'Harald', 'Adicional'),
(15, 'Chocoball', 'chocoball', 'VaBene', 'Adicional'),
(16, 'Jujuba', 'jujuba', 'SimoGomas', 'Adicional'),
(17, 'Confetes Coloridos', 'confetesColoridos', 'Coloreti', 'Adicional'),
(18, 'Cremes Saborazzi', 'cremesSaborazzi', 'Saborazzi', 'Creme'),
(19, 'Polpas de Frutas', 'polpas', 'Sabor Natural', 'Outro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_order`
--

CREATE TABLE `product_order` (
  `idOrder` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `totPrice` decimal(10,2) DEFAULT 0.00,
  `singlePrice` decimal(8,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_version`
--

CREATE TABLE `product_version` (
  `idVersion` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `nameProduct` varchar(50) NOT NULL,
  `sizeProduct` varchar(20) NOT NULL,
  `priceProduct` decimal(8,2) DEFAULT 0.00,
  `priceDate` date NOT NULL,
  `availability` enum('disponivel','indisponivel') DEFAULT 'disponivel',
  `imageURL` varchar(250) DEFAULT 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079685/LogoAcai_x1zv8k.png',
  `flavor` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `product_version`
--

INSERT INTO `product_version` (`idVersion`, `idProduct`, `nameProduct`, `sizeProduct`, `priceProduct`, `priceDate`, `availability`, `imageURL`, `flavor`) VALUES
(1, 1, 'acaiT10', '10l', 110.00, '2025-10-03', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1759519124/Projeto_Acai/Products/acaiT10.jpg', NULL),
(2, 1, 'acaiT5', '5l', 65.00, '2025-10-03', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1759519173/Projeto_Acai/Products/acaiT5.jpg', NULL),
(3, 1, 'acaiT1', '1l', 18.00, '2025-11-03', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758995467/Projeto_Acai/Products/acaiT1.jpg', NULL),
(4, 2, 'colher200', '200 unidades', 30.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg', NULL),
(5, 2, 'colher500', '500 unidades', 60.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg', NULL),
(6, 2, 'colher800', '800 unidades', 45.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311857/caixa-colher_eurc6f.jpg', NULL),
(7, 3, 'cremeCupuacu10', '10l', 80.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', 'Cupuaçu'),
(8, 3, 'cremeMaracuja10', '10l', 80.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', 'Maracujá'),
(9, 3, 'cremeMorango10', '10l', 80.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', 'Morango'),
(10, 3, 'cremeNinho10', '10l', 85.00, '2025-09-25', 'indisponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311867/cremes-frutados_kfdx1f.jpg', 'Ninho'),
(11, 4, 'acaiZero10', '10l', 85.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127161/acaiZero10_prkapg.png', NULL),
(12, 5, 'acaiNinho1', '1l', 17.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127200/acaiZero10_sjfhet.png', NULL),
(13, 5, 'acaiNinho250', '250ml', 4.50, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127200/acaiZero10_sjfhet.png', NULL),
(14, 6, 'morango1', '1kg', 14.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1759502043/Projeto_Acai/Products/morango1.png', NULL),
(15, 7, 'leiteEmPo1', '1kg', 25.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311884/leite_em_po_rkrf0f.jpg', NULL),
(16, 8, 'granola1.5', '1.5kg', 60.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311897/granola_majjmg_o5aufd.jpg', NULL),
(17, 9, 'granola1', '1kg', 20.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127305/granola1_thtano.png', NULL),
(18, 10, 'pacoca150', '150 unidades', 23.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311925/pacoca_xebjbp.jpg', NULL),
(19, 11, 'farofaPacoca1', '1kg', 22.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127182/farofaPacoca1_daggz8.png', NULL),
(20, 12, 'amendoimTriturado1', '1kg', 22.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127121/amendoimTriturado1_whxfpb.png', NULL),
(21, 13, 'ovomaltine1', '1kg', 44.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750106271/ovomaltine_ctpjsl.webp', NULL),
(22, 14, 'gotasChocolate1', '1kg', 28.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079858/gotas_wanvya.jpg', NULL),
(23, 15, 'chocoball1', '1kg', 20.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127346/chocoball1_sqqqrp.png', NULL),
(24, 16, 'jujuba500', '500g', 8.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127379/jujuba500_t3dmzr.png', NULL),
(25, 17, 'confeteColoridos1', '1kg', 35.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1758127425/confete1_trubju.png', NULL),
(26, 18, 'saborazziAvelaP', '10kg', 198.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Avelã Premium'),
(27, 18, 'saborazziAvelaT', '10kg', 168.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Avelã Tradicional'),
(28, 18, 'saborazziChocomaltine', '10kg', 162.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Chocomaltine'),
(29, 18, 'saborazziCocada', '10kg', 174.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Cocada Cremosa'),
(30, 18, 'saborazziCookies', '10kg', 170.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Cookies'),
(31, 18, 'saborazziLeitinho', '10kg', 160.00, '2025-09-26', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Leitinho'),
(32, 18, 'saborazziPacoca', '10kg', 140.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Paçoca'),
(33, 18, 'saborazziSkimoB', '10kg', 130.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Skimo Branco'),
(34, 18, 'saborazziSkimoL', '10kg', 156.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Skimo ao Leite'),
(35, 18, 'saborazziWafer', '10kg', 130.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311876/cremes-saborazzi_dhssx6.jpg', 'Wafer'),
(36, 19, 'polpaAbac', '1 unidade', 1.80, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Abacaxi'),
(37, 19, 'polpaAbacHort', '1 unidade', 1.80, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Hortelã'),
(38, 19, 'polpaAcrl', '1 unidade', 1.80, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Acerola'),
(39, 19, 'polpaAcrlMamao', '1 unidade', 1.80, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Acerola c/Mamão'),
(40, 19, 'polpaCacau', '1 unidade', 2.50, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Cacau'),
(41, 19, 'polpaCaja', '1 unidade', 2.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Caja'),
(42, 19, 'polpaCaju', '1 unidade', 1.80, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Caju'),
(43, 19, 'polpaCupuacu', '1 unidade', 2.50, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Cupuaçu'),
(44, 19, 'polpaGoiaba', '1 unidade', 1.80, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Goiaba'),
(45, 19, 'polpaGraviola', '1 unidade', 2.50, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Graviola'),
(46, 19, 'polpaManga', '1 unidade', 1.80, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Manga'),
(47, 19, 'polpaMangaba', '1 unidade', 2.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Mangaba'),
(48, 19, 'polpaMaracuja', '1 unidade', 3.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Maracuja'),
(49, 19, 'polpaMorango', '1 unidade', 2.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Morango'),
(50, 19, 'polpaUva', '1 unidade', 2.00, '2025-09-25', 'disponivel', 'https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755311732/polpas_lnxxhz.jpg', 'Uva');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_data`
--

CREATE TABLE `user_data` (
  `idUser` int(11) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `userMail` varchar(50) NOT NULL,
  `userPhone` varchar(16) NOT NULL,
  `userPassword` varchar(250) NOT NULL,
  `city` varchar(40) NOT NULL,
  `district` varchar(40) NOT NULL,
  `street` varchar(50) NOT NULL,
  `localNum` varchar(10) NOT NULL,
  `referencePoint` varchar(50) DEFAULT '',
  `state` enum('AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO') DEFAULT 'MG'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `user_data`
--

INSERT INTO `user_data` (`idUser`, `userName`, `userMail`, `userPhone`, `userPassword`, `city`, `district`, `street`, `localNum`, `referencePoint`, `state`) VALUES
(1, 'Ademir 2', 'admin@dominio.com', '(32) 9 7854 0244', '$2y$10$UUybSCbs3zBwsUca6AoOj.Wud9R1PUs8dw7voaBpEMCHKN.VAQ9Jm', 'Cidade Legal', 'Bairro Legal', 'Rua Legal', '993', 'ponto de referência', 'MG'),
(2, 'Cliente Teste 1', 'client@dominio.com', '(35) 9 7824 4343', '$2y$10$GJjEFjXpkKI/LNMsZ.WE8e6zd5XEvMd11iQitZ7/vMwiP9lYALrtu', 'Cidade Top', 'Bairro Legal', 'Rua', '111', '', 'RS'),
--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_data`
--
ALTER TABLE `admin_data`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Índices de tabela `attribute_change`
--
ALTER TABLE `attribute_change`
  ADD PRIMARY KEY (`idChange`,`tableName`,`idAttribute`,`objectChanged`);

--
-- Índices de tabela `change_data`
--
ALTER TABLE `change_data`
  ADD PRIMARY KEY (`idChange`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Índices de tabela `client_data`
--
ALTER TABLE `client_data`
  ADD PRIMARY KEY (`idClient`);

--
-- Índices de tabela `order_data`
--
ALTER TABLE `order_data`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `idClient` (`idClient`);

--
-- Índices de tabela `product_data`
--
ALTER TABLE `product_data`
  ADD PRIMARY KEY (`idProduct`);

--
-- Índices de tabela `product_order`
--
ALTER TABLE `product_order`
  ADD PRIMARY KEY (`idOrder`,`idProduct`),
  ADD KEY `idProduct` (`idProduct`);

--
-- Índices de tabela `product_version`
--
ALTER TABLE `product_version`
  ADD PRIMARY KEY (`idVersion`,`idProduct`,`nameProduct`),
  ADD KEY `product_version_ibfk_1` (`idProduct`);

--
-- Índices de tabela `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `change_data`
--
ALTER TABLE `change_data`
  MODIFY `idChange` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `order_data`
--
ALTER TABLE `order_data`
  MODIFY `idOrder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de tabela `product_data`
--
ALTER TABLE `product_data`
  MODIFY `idProduct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `product_version`
--
ALTER TABLE `product_version`
  MODIFY `idVersion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de tabela `user_data`
--
ALTER TABLE `user_data`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `admin_data`
--
ALTER TABLE `admin_data`
  ADD CONSTRAINT `admin_data_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `user_data` (`idUser`) ON DELETE CASCADE;

--
-- Restrições para tabelas `attribute_change`
--
ALTER TABLE `attribute_change`
  ADD CONSTRAINT `attribute_change_ibfk_1` FOREIGN KEY (`idChange`) REFERENCES `change_data` (`idChange`) ON DELETE CASCADE;

--
-- Restrições para tabelas `change_data`
--
ALTER TABLE `change_data`
  ADD CONSTRAINT `change_data_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `admin_data` (`idAdmin`);

--
-- Restrições para tabelas `client_data`
--
ALTER TABLE `client_data`
  ADD CONSTRAINT `client_data_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `user_data` (`idUser`) ON DELETE CASCADE;

--
-- Restrições para tabelas `order_data`
--
ALTER TABLE `order_data`
  ADD CONSTRAINT `order_data_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client_data` (`idClient`) ON DELETE CASCADE;

--
-- Restrições para tabelas `product_order`
--
ALTER TABLE `product_order`
  ADD CONSTRAINT `product_order_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `order_data` (`idOrder`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_order_ibfk_2` FOREIGN KEY (`idProduct`) REFERENCES `product_version` (`idVersion`) ON DELETE CASCADE;

--
-- Restrições para tabelas `product_version`
--
ALTER TABLE `product_version`
  ADD CONSTRAINT `product_version_ibfk_1` FOREIGN KEY (`idProduct`) REFERENCES `product_data` (`idProduct`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
