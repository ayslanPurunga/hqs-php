<?php
  //verificar se não está logado
  if ( !isset ( $_SESSION["hqs"]["id"] ) ){
    exit;
  }
?>
<div class="container">
	<h1 class="float-left">Listar Cidade</h1>
	<div class="float-right">
		<a href="cadastro/cidade" class="btn btn-success">Novo Registro</a>
		<a href="listar/cidade" class="btn btn-info">Listar Registros</a>
	</div>

	<div class="clearfix"></div>

	<table class="table table-striped table-bordered table-hover" id="tabela">
		<thead>
			<tr>
				<td>ID</td>
				<td>Nome da Cidade</td>
				<td>Estado</td>
				<td>Opções</td>
			</tr>
		</thead>
		<tbody>
			<?php
				//buscar as cidades alfabeticamente
				$sql = "SELECT * FROM cidade 
				ORDER BY cidade";
				$consulta = $pdo->prepare($sql);
				$consulta->execute();

				while ( $dados = $consulta->fetch(PDO::FETCH_OBJ) ) {
					//separar os dados
					$id 		= $dados->id;
					$cidade 	= $dados->cidade;
					$estado 	= $dados->estado;
					//mostrar na tela
					echo '<tr>
						<td>'.$id.'</td>
						<td>'.$cidade.'</td>
						<td>'.$estado.'</td>
						<td>
							<a href="cadastro/cidade/'.$id.'" class="btn btn-success btn-sm">
								<i class="fas fa-edit"></i>
							</a>

			<a href="javascript:excluir('.$id.')" class="btn btn-danger btn-sm"  onclick="excluir"('.$id.')>
								<i class="fas fa-trash"></i>
							</a>
						</td>
					</tr>';
				}
			?>
		</tbody>
	</table>
</div>
<script>
	//funcao para perguntar se deseja excluir
	//se sim direcionar para o endereco de exclusão
	function excluir( id ) {
		//perguntar - função confirm
		if ( confirm ( "Deseja mesmo excluir?" ) ) {
			//direcionar para a exclusao
			location.href="excluir/cidade/"+id;
		}
	}

	//adicionar o dataTable a minha tabela
	$(document).ready( function () {
            $('#tabela').DataTable({
                "language": {
            "lengthMenu": "Mostrando _MENU_ registros por pagina",
            "zeroRecords": "Nenhum arquivo - Desculpe-nos!",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Não contem registros válidos!",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "Busca",
            "Previous": "Anterior",
            "Next": "próximo"
        }
        
            });
        } );
</script>