<?php

// Verificar se não está logado
if (!isset($_SESSION['hqs']['id'])) {
    exit;
}

if ($_POST) {

    include "functions.php";
    include "config/conexao.php";

//recuperar variaveis
    $id = $nome = $cpf = $datanascimento = $telefone = $celular = $email = $senha = $cep = $foto = $cidade = $estado = $endereco = $bairro = "";

    foreach ($_POST as $key => $value) {
    	$$key = trim($value);
    }    

//verificar campos não preenchidos
if ((empty($nome)) || (empty($cpf)) || (empty($datanascimento)) || (empty($email)) || (empty($senha)) || (empty($cep)) || (empty($endereco)) || (empty($bairro)) || (empty($cidade_id)) || (empty($telefone))) {
    echo "<script>alert('Alguns campos estão em branco!');history.back();</script>";
    exit;
}    

$pdo->beginTransaction();

//formatar data
$data =formatar($datanascimento);

$arquivo = time() . "-" . $_SESSION["hqs"]["id"];

if (empty($id)) {
    $sql = "INSERT INTO cliente (nome, cpf, datanascimento, telefone, celular, email, senha, cep, foto, cidade_id, endereço, bairro, complemento) VALUES (:nome, :cpf, :datanascimento, :telefone, :celular, :email, :senha, :cep, :foto,:cidade_id, :endereço, :bairro, :complemento)";

    $consulta = $pdo->prepare($sql);
    $consulta->bindParam(":nome", $nome);
   	$consulta->bindParam(":cpf", $cpf);
   	$consulta->bindParam(":datanascimento", $datanascimento);
   	$consulta->bindParam(":telefone", $telefone);
 	$consulta->bindParam(":celular", $celular);
   	$consulta->bindParam(":email", $email);
	$consulta->bindParam(":senha", $senha);
	$consulta->bindParam(":cep", $cep);
	$consulta->bindParam(":foto", $foto);
	$consulta->bindParam(":cidade_id", $cidade_id);
	$consulta->bindParam(":endereco", $endereco);
	$consulta->bindParam(":bairro", $bairro);
    $consulta->bindParam(":complemento", $complemento);
}else{
    //atualizar os dados
    	if ( !empty ( $_FILES["foto"]["p.jpg"] ) ) 
            $foto = $arquivo;

    $sql = "UPDATE cliente SET nome = :nome, cpf = :cpf, datanascimento = :datanascimento, telefone = :telefone, celular = :celular, email = :email, senha = :senha, cep = :cep, foto = :foto, cidade_id = :cidade_id, endereco = :endereco,
    bairro = :bairro, complemento = :complemento WHERE id = :id limit 1"; 

            $consulta = $pdo->prepare($sql);
            $consulta->bindParam(":nome", $nome);
            $consulta->bindParam(":cpf", $cpf);
            $consulta->bindParam(":datanascimento", $datanascimento);
            $consulta->bindParam(":telefone", $telefone);
            $consulta->bindParam(":celular", $celular);
            $consulta->bindParam(":email", $email);
            $consulta->bindParam(":senha", $senha);
            $consulta->bindParam(":cep", $cep);
            $consulta->bindParam(":foto", $foto);
            $consulta->bindParam(":cidade_id", $cidade_id);
            $consulta->bindParam(":endereco", $endereco);
            $consulta->bindParam(":bairro", $bairro);
            $consulta->bindParam(":complemento", $complemento);

            
    }        
    
    if (!$consulta->execute()) {

        //verifica se o arquivo não está sendo enviado
        if ((empty($_FILES["foto"]["type"])) && (!empty($id))) {
            $pdo->commit();
            echo "<script>alert('Registro salvo com sucesso!');location.href='listar/cliente';</script>";
            exit;
        }

        //verifica se a imagem é JPG
        if ($_FILES["foto"]["type"] != "image/jpeg") {
            echo "<script>alert('Selecione uma imagem JPG válida');history.back();</script>";
            exit;
        }

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], "../fotos/" . $_FILES["foto"]["name"])) {
            // Redimensionar imagem
            $pastaFotos = "../fotos/";
            $imagem = $_FILES["foto"]["name"];
            $nome = $arquivo;
            redimensionarImagem($pastaFotos, $imagem, $nome);

            //salvar no banco de dados
            $pdo->commit();
            echo "<script>alert('Registro salvo com sucesso!');location.href='listar/cliente';</script>";
            exit;
        }

        // Erro ao gravar
        echo "<script>alert('Erro ao salvar arquivo!');history.back();</script>";
        exit;
    }
    exit;
}

echo "<p class='alert alert-danger'>Requisição sem valor!</p>";
?>

}