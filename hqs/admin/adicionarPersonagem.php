<?php
    session_start();
    //verificar se esta logado
if ( !isset ($_SESSION["hqs"]["id"])) {
    exit;
}

//incluir o arquivo de conexao
include "config/conexao.php";

$quadrinho_id = $_GET["quadrinho_id"] ?? "";

//verificar se foi dado POST

if ( $_POST ) {
    //inserir um quadrinho 
    $personagem_id = $_POST["personagem_id"] ?? "";
    $quadrinho_id  = $_POST["quadrinho_id"] ?? "";

    if ( (empty($personagem_id) ) or (empty($quadrinho_id) ) ) {
        echo "<script>alert('Erro ao adicionar personagem');</script>";
        
    } else {

        //inserir dentro do quadrinho_personagem
        $sql = "INSERT INTO quadrinho_personagem VALUES(:quadrinho_id,:personagem_id)";
            $consulta = $pdo->prepare($sql);
            $consulta->bindParam(":quadrinho_id", $quadrinho_id);
            $consulta->bindParam(":personagem_id", $personagem_id);
            $consulta->execute();

            if ( !$consulta->execute() ) {
                echo "<script>alert('Não foi possivel inserir o personagem neste quadrinho');</script>";
            }
         } 
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Personagem</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Custom fonts for this template-->
  	<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">  

  	<!-- Custom styles for this template-->
  	<link href="css/sb-admin-2.min.css" rel="stylesheet">

    <script src="vendor/jquery/jquery.min.js"></script>  

</head>
<body>
    <h4>Personagens deste quadrinho:</h4>
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <td>Personagens</td>
                <td>Opções</td>
            </tr>
        </thead>
    <?php
        $sql = "SELECT q.id qid, p.id pid, p.nome FROM quadrinho_personagem qp INNER JOIN personagem p ON ( p.id = qp.personagem_id ) INNER JOIN quadrinho q on ( q.id = qp.quadrinho_id ) WHERE qp.quadrinho_id = :quadrinho_id ORDER BY p.nome";

        $consulta = $pdo->prepare( $sql );
        $consulta->bindParam(":quadrinho_id", $quadrinho_id);
        $consulta->execute();

    while ( $dados = $consulta->fetch(PDO::FETCH_OBJ) ) {
        ?>
            <tr>
                <td><?=$dados->nome;?></td>
                <td>
                    <a href="javascript:excluir(<?=$dados->pid;?>=$dados->qid;?>)" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        <?php
        }

?>
    </table>   

    <script type="text/javascript">
    //função para excluir o personagem
       function excluir(personagem_id,quadrinho_id) {
           //perguntar se deseja mesmo excluir
           if ( confirm ( "Deseja mesmo excluir?" ) ){
               //direcionar para uma página para excluir o personagem e retornar para esta listagem
               location.href="excluirPersonagem.php?personagem_id="+personagem_id+"&quadrinho_id"+quadrinho_id;
           }
       } 

    
    </script> 
</body>
</html>