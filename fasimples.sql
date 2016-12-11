-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 11-Dez-2016 às 15:45
-- Versão do servidor: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fasimples`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `IDCLIENTE` bigint(20) NOT NULL,
  `CPF` bigint(20) NOT NULL,
  `SALDO` float DEFAULT NULL,
  `SENHA` varchar(255) NOT NULL,
  `DATA_CADASTRO` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`IDCLIENTE`, `CPF`, `SALDO`, `SENHA`, `DATA_CADASTRO`) VALUES
(1, 37397042848, 400, '81dc9bdb52d04dc20036dbd8313ed055', '2016-12-11 02:28:25'),
(2, 41135738831, 300, '81dc9bdb52d04dc20036dbd8313ed055', '2016-12-11 02:29:16'),
(3, 39105904897, 5000, '81dc9bdb52d04dc20036dbd8313ed055', '0000-00-00 00:00:00'),
(4, 62982034786, 5000, 'd41d8cd98f00b204e9800998ecf8427e', '0000-00-00 00:00:00'),
(5, 62982034786, 5000, '379ef4bd50c30e261ccfb18dfc626d9f', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `estabelecimento`
--

CREATE TABLE `estabelecimento` (
  `IDESTABELECIMENTO` bigint(20) NOT NULL,
  `RAZAOSOCIAL` varchar(40) NOT NULL,
  `DATA_CADASTRO` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `estabelecimento`
--

INSERT INTO `estabelecimento` (`IDESTABELECIMENTO`, `RAZAOSOCIAL`, `DATA_CADASTRO`) VALUES
(1, 'PADARIA NOSSO PAO', '2016-12-11 02:30:25'),
(2, 'AÇOUGUE BOACARNE', '2016-12-11 02:30:25');

-- --------------------------------------------------------

--
-- Estrutura da tabela `smstoken`
--

CREATE TABLE `smstoken` (
  `IDSMS` int(11) NOT NULL,
  `cliente_IDCLIENTE` bigint(20) DEFAULT NULL,
  `TIPO_TRANSACAO` int(11) DEFAULT NULL,
  `DATA_REGISTRO` datetime DEFAULT NULL,
  `TOKEN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `smstoken`
--

INSERT INTO `smstoken` (`IDSMS`, `cliente_IDCLIENTE`, `TIPO_TRANSACAO`, `DATA_REGISTRO`, `TOKEN`) VALUES
(1, 2, NULL, '2016-12-11 03:10:46', 0),
(2, 2, NULL, '2016-12-11 03:11:40', 8),
(3, 2, NULL, '2016-12-11 03:52:36', 59453),
(4, 2, NULL, '2016-12-11 03:54:21', 0),
(5, 2, NULL, '2016-12-11 03:55:53', 21),
(6, 2, NULL, '2016-12-11 03:56:30', 4920136),
(7, 2, NULL, '2016-12-11 03:56:46', 0),
(8, 2, NULL, '2016-12-11 03:57:24', 0),
(9, 2, NULL, '2016-12-11 03:58:45', 0),
(10, 2, NULL, '2016-12-11 03:59:59', 1),
(11, 2, NULL, '2016-12-11 04:00:12', 0),
(12, 2, NULL, '2016-12-11 04:02:16', 0),
(13, 2, NULL, '2016-12-11 04:02:24', 0),
(14, 2, NULL, '2016-12-11 04:06:24', 26),
(15, 2, NULL, '2016-12-11 04:07:05', 0),
(16, 1, NULL, '2016-12-11 08:08:51', 7),
(17, 1, NULL, '2016-12-11 08:10:11', 703511408),
(18, 1, NULL, '2016-12-11 08:10:12', 74374),
(19, 1, NULL, '2016-12-11 08:10:28', 83),
(20, 1, NULL, '2016-12-11 08:11:31', 614702957),
(21, 1, NULL, '2016-12-11 08:16:23', 0),
(22, 2, NULL, '2016-12-11 08:16:49', 420),
(23, 2, NULL, '2016-12-11 08:18:59', 63),
(24, 2, NULL, '2016-12-11 08:20:48', 4),
(25, 2, NULL, '2016-12-11 09:43:43', 0),
(26, 3, NULL, '2016-12-11 11:38:49', 0),
(27, 3, NULL, '2016-12-11 11:39:58', 572),
(28, 4, NULL, '2016-12-11 11:56:24', 0),
(29, 2, NULL, '2016-12-11 12:03:16', 0),
(30, 2, NULL, '2016-12-11 12:14:06', 2147483647),
(31, 2, NULL, '2016-12-11 12:15:33', 8),
(32, 2, NULL, '2016-12-11 12:18:54', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `telefone`
--

CREATE TABLE `telefone` (
  `IDTELEFONE` bigint(20) NOT NULL,
  `cliente_IDCLIENTE` bigint(20) NOT NULL,
  `DDD` int(11) DEFAULT NULL,
  `NUMERO` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `telefone`
--

INSERT INTO `telefone` (`IDTELEFONE`, `cliente_IDCLIENTE`, `DDD`, `NUMERO`) VALUES
(1, 1, 11, 958903739),
(2, 2, 11, 979657744),
(3, 3, 11, 963431667),
(4, 4, 51, 999770955);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_transacao`
--

CREATE TABLE `tipo_transacao` (
  `IDTIPO` int(11) NOT NULL,
  `DESCRICAO` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tipo_transacao`
--

INSERT INTO `tipo_transacao` (`IDTIPO`, `DESCRICAO`) VALUES
(0, 'saque'),
(1, 'deposito'),
(2, 'pagamento');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacao`
--

CREATE TABLE `transacao` (
  `IDTRANSACAO` bigint(20) NOT NULL,
  `tipo_transacao_IDTIPO` int(11) NOT NULL,
  `cliente_IDCLIENTE` bigint(20) NOT NULL,
  `estabelecimento_IDESTABELECIMENTO` bigint(20) NOT NULL,
  `DATA_REGISTRO` datetime DEFAULT NULL,
  `VALOR_TRANSACAO` float DEFAULT NULL,
  `cliente_IDCLIENTE_PAGADOR` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `transacao`
--

INSERT INTO `transacao` (`IDTRANSACAO`, `tipo_transacao_IDTIPO`, `cliente_IDCLIENTE`, `estabelecimento_IDESTABELECIMENTO`, `DATA_REGISTRO`, `VALOR_TRANSACAO`, `cliente_IDCLIENTE_PAGADOR`) VALUES
(0, 1, 41135738831, 1, '2016-12-11 12:40:33', 200, 39105904897),
(1, 2, 1, 1, '2016-12-11 08:31:49', 100, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`IDCLIENTE`);

--
-- Indexes for table `estabelecimento`
--
ALTER TABLE `estabelecimento`
  ADD PRIMARY KEY (`IDESTABELECIMENTO`);

--
-- Indexes for table `smstoken`
--
ALTER TABLE `smstoken`
  ADD PRIMARY KEY (`IDSMS`);

--
-- Indexes for table `telefone`
--
ALTER TABLE `telefone`
  ADD PRIMARY KEY (`IDTELEFONE`),
  ADD KEY `cliente_IDCLIENTE` (`cliente_IDCLIENTE`);

--
-- Indexes for table `tipo_transacao`
--
ALTER TABLE `tipo_transacao`
  ADD PRIMARY KEY (`IDTIPO`);

--
-- Indexes for table `transacao`
--
ALTER TABLE `transacao`
  ADD PRIMARY KEY (`IDTRANSACAO`),
  ADD KEY `estabelecimento_IDESTABELECIMENTO` (`estabelecimento_IDESTABELECIMENTO`),
  ADD KEY `cliente_IDCLIENTE` (`cliente_IDCLIENTE`),
  ADD KEY `tipo_transacao_IDTIPO` (`tipo_transacao_IDTIPO`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
