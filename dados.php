<?php
// ============================================================
//  BANCO DE LEITE HUMANO — MARÍLIA
//  dados.php — "banco de dados" em arrays PHP
//  Em produção real, cada array vira uma tabela SQL.
//  ATENÇÃO: Este arquivo contém apenas dados. Não incluir auth aqui.
// ============================================================

// ------------------------------------------------------------
// USUÁRIOS — login e perfis de acesso
// perfis: admin | coletador | nutricionista
// ------------------------------------------------------------
$usuarios = [
    [
        "id"     => 1,
        "nome"   => "Dra. Ana Lima",
        "login"  => "admin",
        "senha"  => "1234",
        "perfil" => "admin"
    ],
    [
        "id"     => 2,
        "nome"   => "Marcos Silva",
        "login"  => "marcos",
        "senha"  => "1234",
        "perfil" => "coletador"
    ],
    [
        "id"     => 3,
        "nome"   => "Nutr. Carla Souza",
        "login"  => "carla",
        "senha"  => "1234",
        "perfil" => "nutricionista"
    ],
];

// ------------------------------------------------------------
// DOADORAS — cadastro completo das mães
// zona define o dia da coleta:
//   Norte=segunda | Sul=terça | Oeste=quarta | Leste=quinta | Rural=sexta
// sorologia: "negativa" = apta | qualquer outro valor = removida
// status: "ativa" | "inativa" | "removida"
// ------------------------------------------------------------
$doadoras = [
    [
        "id"                  => 1,
        "nome"                => "Maria Aparecida Santos",
        "cartao_sus"          => "898 0043 1234 5678",
        "data_nascimento"     => "1995-06-14",
        "telefone"            => "(14) 99801-2233",
        "zona"                => "Norte",
        "endereco"            => "Rua das Flores, 42 — Jd. América",
        "vacinas_ok"          => true,
        "fuma"                => false,
        "alcool"              => false,
        "medicacao"           => "",
        "transfusao"          => false,
        "tatuagem"            => false,
        "agua_potavel"        => true,
        "equipamento_frio"    => "freezer",
        "sorologia"           => "negativa",
        "status"              => "ativa",
        "data_parto"          => "2025-03-10",
        "nome_bebe"           => "Pedro Santos",
        "peso_bebe_kg"        => 2.1,
        "data_cadastro"       => "2025-03-15",
    ],
    [
        "id"                  => 2,
        "nome"                => "Juliana Ferreira Costa",
        "cartao_sus"          => "700 0012 9876 5432",
        "data_nascimento"     => "1998-11-22",
        "telefone"            => "(14) 99711-4455",
        "zona"                => "Sul",
        "endereco"            => "Av. Brasil, 200 — Centro",
        "vacinas_ok"          => true,
        "fuma"                => false,
        "alcool"              => false,
        "medicacao"           => "",
        "transfusao"          => false,
        "tatuagem"            => false,
        "agua_potavel"        => true,
        "equipamento_frio"    => "duplex",
        "sorologia"           => "negativa",
        "status"              => "ativa",
        "data_parto"          => "2025-04-02",
        "nome_bebe"           => "Sofia Costa",
        "peso_bebe_kg"        => 1.8,
        "data_cadastro"       => "2025-04-05",
    ],
    [
        "id"                  => 3,
        "nome"                => "Renata Oliveira Melo",
        "cartao_sus"          => "602 0077 1111 2222",
        "data_nascimento"     => "1992-03-08",
        "telefone"            => "(14) 98822-6677",
        "zona"                => "Oeste",
        "endereco"            => "Rua Tiradentes, 88 — Vila Nova",
        "vacinas_ok"          => true,
        "fuma"                => false,
        "alcool"              => false,
        "medicacao"           => "",
        "transfusao"          => false,
        "tatuagem"            => true,
        "agua_potavel"        => true,
        "equipamento_frio"    => "refrigerador",
        "sorologia"           => "negativa",
        "status"              => "ativa",
        "data_parto"          => "2025-04-18",
        "nome_bebe"           => "Lucas Melo",
        "peso_bebe_kg"        => 2.4,
        "data_cadastro"       => "2025-04-20",
    ],
    [
        "id"                  => 4,
        "nome"                => "Fernanda Lima Braga",
        "cartao_sus"          => "511 0033 5555 6666",
        "data_nascimento"     => "2000-07-30",
        "telefone"            => "(14) 97733-8899",
        "zona"                => "Leste",
        "endereco"            => "Rua São Paulo, 310 — Jardim Europa",
        "vacinas_ok"          => true,
        "fuma"                => false,
        "alcool"              => false,
        "medicacao"           => "",
        "transfusao"          => false,
        "tatuagem"            => false,
        "agua_potavel"        => true,
        "equipamento_frio"    => "freezer",
        "sorologia"           => "negativa",
        "status"              => "ativa",
        "data_parto"          => "2025-05-01",
        "nome_bebe"           => "Ana Braga",
        "peso_bebe_kg"        => 1.6,
        "data_cadastro"       => "2025-05-03",
    ],
    [
        "id"                  => 5,
        "nome"                => "Patricia Souza Nunes",
        "cartao_sus"          => "400 0055 7777 8888",
        "data_nascimento"     => "1990-12-01",
        "telefone"            => "(14) 96644-0011",
        "zona"                => "Rural",
        "endereco"            => "Estrada do Maracá, km 4",
        "vacinas_ok"          => true,
        "fuma"                => false,
        "alcool"              => false,
        "medicacao"           => "",
        "transfusao"          => false,
        "tatuagem"            => false,
        "agua_potavel"        => false,
        "equipamento_frio"    => "freezer",
        "sorologia"           => "negativa",
        "status"              => "ativa",
        "data_parto"          => "2025-05-10",
        "nome_bebe"           => "João Nunes",
        "peso_bebe_kg"        => 2.0,
        "data_cadastro"       => "2025-05-12",
    ],
];

// ------------------------------------------------------------
// FRASCOS — ciclo completo do leite
// status: coletado | pasteurizado | distribuido | descartado
// tipo_leite: C=Colostro | T=Transição | M=Maduro
// ------------------------------------------------------------
$frascos = [
    [
        "id"              => 1,
        "id_doadora"      => 1,
        "tipo_leite"      => "C",
        "volume_ml"       => 150,
        "data_coleta"     => "2025-05-19",
        "forma_extracao"  => "manual",
        "estado_coleta"   => "congelado",
        "temp_saida"      => -4.0,
        "temp_chegada"    => -3.0,
        "status"          => "distribuido",
        "data_pasteur"    => "2025-05-20",
        "crematocrito"    => 6.2,
        "dornic"          => 7,
        "bgbi"            => "aprovado",
        "aprovado"        => true,
        "motivo_descarte" => "",
    ],
    [
        "id"              => 2,
        "id_doadora"      => 2,
        "tipo_leite"      => "M",
        "volume_ml"       => 200,
        "data_coleta"     => "2025-05-20",
        "forma_extracao"  => "eletrica",
        "estado_coleta"   => "refrigerado",
        "temp_saida"      => 4.0,
        "temp_chegada"    => 5.5,
        "status"          => "pasteurizado",
        "data_pasteur"    => "2025-05-21",
        "crematocrito"    => 4.1,
        "dornic"          => 8,
        "bgbi"            => "aprovado",
        "aprovado"        => true,
        "motivo_descarte" => "",
    ],
    [
        "id"              => 3,
        "id_doadora"      => 3,
        "tipo_leite"      => "T",
        "volume_ml"       => 180,
        "data_coleta"     => "2025-05-21",
        "forma_extracao"  => "manual",
        "estado_coleta"   => "congelado",
        "temp_saida"      => -4.0,
        "temp_chegada"    => -3.5,
        "status"          => "pasteurizado",
        "data_pasteur"    => "2025-05-22",
        "crematocrito"    => 5.0,
        "dornic"          => 7,
        "bgbi"            => "aprovado",
        "aprovado"        => true,
        "motivo_descarte" => "",
    ],
    [
        "id"              => 4,
        "id_doadora"      => 4,
        "tipo_leite"      => "C",
        "volume_ml"       => 120,
        "data_coleta"     => "2025-05-22",
        "forma_extracao"  => "eletrica",
        "estado_coleta"   => "congelado",
        "temp_saida"      => -4.0,
        "temp_chegada"    => -3.0,
        "status"          => "coletado",
        "data_pasteur"    => "",
        "crematocrito"    => 0,
        "dornic"          => 0,
        "bgbi"            => "",
        "aprovado"        => false,
        "motivo_descarte" => "",
    ],
    [
        "id"              => 5,
        "id_doadora"      => 1,
        "tipo_leite"      => "M",
        "volume_ml"       => 250,
        "data_coleta"     => "2025-05-22",
        "forma_extracao"  => "manual",
        "estado_coleta"   => "congelado",
        "temp_saida"      => -4.0,
        "temp_chegada"    => -3.0,
        "status"          => "descartado",
        "data_pasteur"    => "2025-05-22",
        "crematocrito"    => 0,
        "dornic"          => 18,
        "bgbi"            => "reprovado",
        "aprovado"        => false,
        "motivo_descarte" => "Acidez (Dornic) acima do limite permitido.",
    ],
];

// ------------------------------------------------------------
// RECEPTORES — recém-nascidos que recebem o leite
// tipo_leite: tipo de leite prescrito pelo médico
// ------------------------------------------------------------
$receptores = [
    [
        "id"         => 1,
        "nome_rn"    => "RN de Beatriz Alves",
        "leito"      => "UTI-03",
        "hospital"   => "Hospital Conjunto Hospitalar de Marília",
        "tipo_leite" => "C",
        "volume_ml"  => 150,
        "medico"     => "Dr. Roberto Faria",
        "data_pedido"=> "2025-05-21",
        "status"     => "atendido",
    ],
    [
        "id"         => 2,
        "nome_rn"    => "RN de Claudia Rocha",
        "leito"      => "UTI-07",
        "hospital"   => "Hospital Santa Casa de Marília",
        "tipo_leite" => "T",
        "volume_ml"  => 180,
        "medico"     => "Dra. Mariana Lopes",
        "data_pedido"=> "2025-05-22",
        "status"     => "aguardando",
    ],
    [
        "id"         => 3,
        "nome_rn"    => "RN de Andréia Campos",
        "leito"      => "UTI-01",
        "hospital"   => "Hospital Conjunto Hospitalar de Marília",
        "tipo_leite" => "M",
        "volume_ml"  => 200,
        "medico"     => "Dr. Roberto Faria",
        "data_pedido"=> "2025-05-23",
        "status"     => "aguardando",
    ],
];

// ------------------------------------------------------------
// DISTRIBUIÇÕES — o link que fecha a rastreabilidade
// Liga id_frasco (pasteurizado) ao id_receptor (bebê na UTI)
// ------------------------------------------------------------
$distribuicoes = [
    [
        "id"           => 1,
        "id_frasco"    => 1,
        "id_receptor"  => 1,
        "data_entrega" => "2025-05-22",
        "hora"         => "08:30",
        "temp_caixa"   => 4.0,
        "entregador"   => "Marcos Silva",
        "ass_entrega"  => "MS",
        "ass_recebe"   => "RF",
        "setor"        => "UTI Neonatal",
        "devolvido"    => false,
        "data_devolucao" => "",
    ],
];