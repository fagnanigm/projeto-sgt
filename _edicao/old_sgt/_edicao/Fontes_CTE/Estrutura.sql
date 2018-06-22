USE [master]
GO
/****** Object:  Database [NecBrasil_Emissor_CTE]    Script Date: 20/03/2018 09:54:11 ******/
CREATE DATABASE [NecBrasil_Emissor_CTE] ON  PRIMARY 
( NAME = N'NecBrasil_Emissor_CTE', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL10_50.EMISSOR_CTE\MSSQL\DATA\NecBrasil_Emissor_CTE.mdf' , SIZE = 3072KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB )
 LOG ON 
( NAME = N'NecBrasil_Emissor_CTE_log', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL10_50.EMISSOR_CTE\MSSQL\DATA\NecBrasil_Emissor_CTE_log.ldf' , SIZE = 1024KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET COMPATIBILITY_LEVEL = 100
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [NecBrasil_Emissor_CTE].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET ARITHABORT OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET  DISABLE_BROKER 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET RECOVERY SIMPLE 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET  MULTI_USER 
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET DB_CHAINING OFF 
GO
USE [NecBrasil_Emissor_CTE]
GO
/****** Object:  Table [dbo].[MunicipioG]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[MunicipioG](
	[estado] [varchar](2) NOT NULL,
	[cd_municipio] [int] NOT NULL,
	[nome] [varchar](50) NULL,
	[status] [varchar](1) NULL,
	[cd_municipio_ibge] [varchar](4) NULL,
	[cd_municipio_estado] [int] NULL,
	[cd_municipio_concla] [int] NULL CONSTRAINT [DF_MunicipioG_cd_municipio_concla]  DEFAULT ((0)),
 CONSTRAINT [PK_MunicipioG] PRIMARY KEY NONCLUSTERED 
(
	[estado] ASC,
	[cd_municipio] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_CFOP_Omie]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_CFOP_Omie](
	[Codigo_CFOP] [int] NOT NULL,
	[Descricao] [varchar](200) NULL,
	[Observacao] [varchar](400) NULL,
 CONSTRAINT [PK_SGT_CFOP_Omie] PRIMARY KEY CLUSTERED 
(
	[Codigo_CFOP] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Clientes_Omie]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Clientes_Omie](
	[Codigo_CLiente] [int] NOT NULL,
	[Razao_Social] [varchar](200) NULL,
	[Nome_Fantasia] [varchar](200) NULL,
	[Endereco] [varchar](200) NULL,
	[Codigo_Municipio_IBGE] [int] NULL,
	[CNPJ_CPF] [varchar](14) NULL,
	[Pais] [varchar](50) NULL,
	[CEP] [varchar](8) NULL,
	[Inscricao_Estadual] [varchar](20) NULL,
	[Telefone] [varchar](11) NULL,
	[Tipo] [int] NOT NULL,
 CONSTRAINT [PK_SGT_Clientes_Omie] PRIMARY KEY CLUSTERED 
(
	[Codigo_CLiente] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico](
	[Codigo_Empresa] [int] NOT NULL,
	[Codigo_Estabelecimento] [int] NOT NULL,
	[Codigo_CTE] [int] NOT NULL,
	[Geral_Tipo_CTE] [int] NULL,
	[Geral_Tipo_Servico] [int] NULL,
	[Geral_Data_Emissao] [datetime] NULL,
	[Geral_CFOP] [varchar](4) NULL,
	[Geral_Codigo_Natureza] [int] NULL,
	[Geral_Cidade_Origem_Codigo_IBGE] [bigint] NULL,
	[Geral_Cidade_Destino_Codigo_IBGE] [bigint] NULL,
	[Geral_Codigo_Remetente] [int] NULL,
	[Geral_Codigo_Destinatario] [int] NULL,
	[Geral_Codigo_Tomador] [int] NULL,
	[Carga_Valor] [numeric](15, 2) NULL,
	[Carga_Produto_Predominante] [varchar](200) NULL,
	[Carga_Outras_Caracteristicas] [varchar](200) NULL,
	[Rodoviario_RNTRC] [varchar](8) NULL,
	[Cobranca_Servico_Valor_Total_Servico] [numeric](15, 2) NULL,
	[Cobranca_Servico_Valor_Receber] [numeric](15, 2) NULL,
	[Cobranca_Servico_Forma_Pagamento] [int] NULL,
	[Cobranca_Servico_Valor_Aproximado_Tributos] [numeric](15, 2) NULL,
	[Cobranca_ICMS_CST] [int] NULL,
	[Cobranca_ICMS_Base] [numeric](15, 2) NULL,
	[Cobranca_ICMS_Aliquota] [numeric](15, 2) NULL,
	[Cobranca_ICMS_Valor] [numeric](15, 2) NULL,
	[Cobranca_ICMS_Percentual_Reducao_Base_Calculo] [numeric](5, 2) NULL,
	[Cobranca_ICMS_Credito] [numeric](5, 2) NULL,
	[Cobranca_Partilha_ICMS_Valor_Base_Calculo] [numeric](15, 2) NULL,
	[Cobranca_Partilha_ICMS_Aliquota_Interna_UF_Termino] [numeric](15, 2) NULL,
	[Cobranca_Partilha_ICMS_Aliquota_Interestadual] [numeric](15, 2) NULL,
	[Cobranca_Partilha_ICMS_Porcentagem_Partilha_UF_Termino] [numeric](5, 2) NULL,
	[Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Inicio] [numeric](15, 2) NULL,
	[Cobranca_Partilha_ICMS_Valor_ICMS_Partilha_UF_Termino] [numeric](15, 2) NULL,
	[Cobranca_Partilha_ICMS_Porcentagem_ICMS_FCP_UF_Termino] [numeric](5, 2) NULL,
	[Cobranca_Partilha_ICMS_Valor_ICMS_FCP_UF_Termino] [numeric](15, 2) NULL,
	[Cobranca_Observacoes_Gerais] [varchar](2000) NULL,
	[Cobranca_Entrega_Prevista] [datetime] NULL,
	[Fatura_Numero] [int] NULL,
	[Fatura_Valor_Origem] [numeric](15, 2) NULL,
	[Fatura_Valor_Desconto] [numeric](15, 2) NULL,
	[Fatura_Valor] [numeric](15, 2) NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico] PRIMARY KEY CLUSTERED 
(
	[Codigo_Empresa] ASC,
	[Codigo_Estabelecimento] ASC,
	[Codigo_CTE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Cargas]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Cargas](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Carga] [int] NOT NULL,
	[Codigo_Unidade_Medida] [varchar](10) NULL,
	[Tipo_Medida] [varchar](200) NULL,
	[Quantidade_Carga] [numeric](15, 2) NULL,
 CONSTRAINT [PK_SGT_Cargas] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Carga] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Documento] [int] NOT NULL,
	[Tipo_Documento] [int] NULL,
	[Nota_Fiscal_Serie] [int] NULL,
	[Nota_Fiscal_Numero] [int] NULL,
	[Nota_Fiscal_Data_Emissao] [datetime] NULL,
	[Nota_Fiscal_CFOP] [varchar](4) NULL,
	[Nota_Fiscal_B_C_ICMS] [numeric](15, 2) NULL,
	[Nota_Fiscal_B_C_ICMS_ST] [numeric](15, 2) NULL,
	[Nota_Fiscal_Valor_ICMS] [numeric](15, 2) NULL,
	[Nota_Fiscal_Valor_ICMS_ST] [numeric](15, 2) NULL,
	[Nota_Fiscal_Valor_Produtos] [numeric](15, 2) NULL,
	[Nota_Fiscal_Valor_Nota] [numeric](15, 2) NULL,
	[Nota_Fiscal_Eletronica_Chave_Acesso] [varchar](44) NULL,
	[Outros_Documentos_Data_Emissao] [datetime] NULL,
	[Outros_Documentos_Documento_Origem] [int] NULL,
	[Outros_Documentos_Descricao] [varchar](200) NULL,
	[Outros_Documentos_Valor] [numeric](15, 2) NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico_Documentos] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Documento] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Documento_Transporte_Anterior] [int] NOT NULL,
	[Razao_Social] [varchar](200) NULL,
	[CPF_CNPJ] [varchar](18) NULL,
	[Inscricao_Estadual] [varchar](20) NULL,
	[UF] [varchar](2) NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Documento_Transporte_Anterior] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Eletronico]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Eletronico](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Documento_Transporte_Anterior] [int] NOT NULL,
	[Codigo_Documento_Transporte_Anterior_Eletronico] [int] NOT NULL,
	[Chave_Acesso] [varchar](44) NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Eletronico] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Documento_Transporte_Anterior] ASC,
	[Codigo_Documento_Transporte_Anterior_Eletronico] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Papel]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Papel](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Documento_Transporte_Anterior] [int] NOT NULL,
	[Codigo_Documento_Transporte_Anterior_Papel] [int] NOT NULL,
	[Tipo] [int] NULL,
	[Serie] [varchar](3) NULL,
	[Sub_Serie] [varchar](2) NULL,
	[Numero] [varchar](20) NULL,
	[Data_Emissao] [datetime] NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico_Documentos_Transporte_Anterior_Papel] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Documento_Transporte_Anterior] ASC,
	[Codigo_Documento_Transporte_Anterior_Papel] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Motoristas]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Motoristas](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Motorista] [int] NOT NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico_Motoristas] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Motorista] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Prestacoes]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Prestacoes](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Prestacao] [int] NOT NULL,
	[Nome] [varchar](200) NULL,
	[Valor] [numeric](15, 2) NULL,
 CONSTRAINT [PK_SGT_Prestacoes] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Prestacao] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Seguros]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Seguros](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Seguro] [int] NOT NULL,
	[Responsavel_Seguro] [int] NULL,
	[Nome_Seguradora] [varchar](200) NULL,
	[Numero_Apolice] [varchar](50) NULL,
	[Numero_Averbacao] [varchar](50) NULL,
	[Valor_Carga_Efeito_Averbacao] [numeric](15, 2) NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico_Seguros] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Seguro] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Conhecimento_Transporte_Eletronico_Veiculos]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SGT_Conhecimento_Transporte_Eletronico_Veiculos](
	[Codigo_CTE] [int] NOT NULL,
	[Codigo_Veiculo] [int] NOT NULL,
 CONSTRAINT [PK_SGT_Conhecimento_Transporte_Eletronico_Veiculos] PRIMARY KEY CLUSTERED 
(
	[Codigo_CTE] ASC,
	[Codigo_Veiculo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[SGT_Motoristas]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Motoristas](
	[Codigo_Motorista] [int] NOT NULL,
	[CPF] [varchar](11) NULL,
	[Nome] [varchar](200) NULL,
 CONSTRAINT [PK_SGT_Motoristas] PRIMARY KEY CLUSTERED 
(
	[Codigo_Motorista] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Natureza]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Natureza](
	[Codigo_Natureza] [int] NOT NULL,
	[Descricao_Natureza] [varchar](200) NULL,
	[CFOP_Dentro_Estado] [varchar](4) NULL,
	[CFOP_Fora_Estado] [varchar](4) NULL,
	[Finalidade] [int] NULL,
	[Calculo_Automatico_Tributos] [bit] NULL,
 CONSTRAINT [PK_SGT_Natureza] PRIMARY KEY CLUSTERED 
(
	[Codigo_Natureza] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Usuarios]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Usuarios](
	[Codigo_Cliente] [int] NOT NULL,
	[Email] [varchar](100) NOT NULL,
	[CNPJ] [varchar](20) NOT NULL,
	[Senha] [varchar](100) NOT NULL,
	[Nome_Cliente] [varchar](100) NULL,
 CONSTRAINT [PK_SGT_Usuarios] PRIMARY KEY CLUSTERED 
(
	[Codigo_Cliente] ASC,
	[CNPJ] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[SGT_Veiculos]    Script Date: 20/03/2018 09:54:11 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[SGT_Veiculos](
	[Codigo_Veiculo] [int] NOT NULL,
	[Renavam] [varchar](20) NULL,
	[Placa] [varchar](10) NULL,
	[Tipo_Veiculo] [int] NULL,
	[Tipo_Carroceria] [int] NULL,
	[Tipo_Rodado] [int] NULL,
	[Tara_Kg] [int] NULL,
	[Capacidade_Kg] [int] NULL,
	[Capacidade_M3] [int] NULL,
	[UF_Veiculo] [varchar](2) NULL,
 CONSTRAINT [PK_SGT_Veiculos] PRIMARY KEY CLUSTERED 
(
	[Codigo_Veiculo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
USE [master]
GO
ALTER DATABASE [NecBrasil_Emissor_CTE] SET  READ_WRITE 
GO
