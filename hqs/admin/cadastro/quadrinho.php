<?php
//verificar se não está logado
if (!isset($_SESSION["hqs"]["id"])) {
	exit;
}

// iniciando as variaveis para evitar erros
$titulo = $data = $numero = $valor = $resumo = $tipo_id = $editora_id  = $capa = "";


//se nao existe o id
if ( !isset ( $id ) ) $id = "";

//verificar se existe um id
if ( !empty ( $id ) ) {
	//selecionar os dados do banco
	$sql = "select * from quadrinho 
		where id = ? limit 1";
	$consulta = $pdo->prepare($sql);
	$consulta->bindParam(1, $id); 
	//$id - linha 255 do index.php
	$consulta->execute();
	$dados  = $consulta->fetch(PDO::FETCH_OBJ);

		//separar os dados
		$id 	= $dados->id;
		$titulo = $dados->titulo;
		$data 	= $dados->data;
		$numero = $dados->numero;
		$valor	= $dados->valor;
		$resumo = $dados->resumo;
		$tipo_id = $dados->tipo_id;
		$editora_id = $dados->editora_id;
		$capa 	= $dados->capa;

		$imagem = "../fotos/".$capa."p.jpg"; 
	} 

?>
<div class="container">
	<h1 class="float-left">Cadastro de Quadrinho</h1>
	<div class="float-right">
		<a href="cadastro/quadrinho" class="btn btn-success">Novo Registro</a>
		<a href="listar/quadrinho" class="btn btn-info">Listar Registros</a>
	</div>

	<div class="clearfix"></div>

	<form name="formCadastro" method="post" action="salvar/quadrinho" data-parsley-validate enctype="multipart/form-data">

		<label for="id">ID</label>
		<input type="text" name="id" id="id" readonly class="form-control" value="<?= $id; ?>">

		<label for="titulo">Título do Quadrinho</label>
		<input type="text" name="titulo" id="titulo" class="form-control"
		 required data-parsley-required-message="Por favor, preencha este campo" value="<?= $titulo; ?>">

		<label for="tipo_id">Tipo de Quadrinho</label>
		<select name="tipo_id" id="tipo_id" class="form-control"
		 required data-parsley-required-message="Selecione uma opção" value="<?= $tipo_id; ?>">
			<option value="<?= $tipo_id; ?>"></option>
			<?php
			$sql = "select id, tipo from tipo
			order by tipo";
			$consulta = $pdo->prepare($sql);
			$consulta->execute();

			while ($d = $consulta->fetch(PDO::FETCH_OBJ)) {
				//separar os dados
				$id 	= $d->id;
				$tipo 	= $d->tipo;

				echo '<option value="' . $id . '">' . $tipo . '</option>';
			}

			?>
		</select>

		<label for="editora_id">Editora</label>
		<select name="editora_id" id="editora_id" class="form-control" 
		required data-parsley-required-message="Selecione uma editora" value="<?= $editora_id; ?>">
			<option value="<?= $editora_id; ?>"></option>
			
			<?php
			$sql = "select id, nome from editora 
				order by nome";
			$consulta = $pdo->prepare($sql);
			$consulta->execute();

			while ($d = $consulta->fetch(PDO::FETCH_OBJ)) {
				//separar os dados
				$id 	= $d->id;
				$nome 	= $d->nome;
				echo '<option value="' . $id . '">' . $nome . '</option>';
			}
			?>
		</select>

		<script type="text/javascript">
			$("#editora_id").val(<?=$editora_id;?>);
		</script>

		<?php 
			//variavel R com required
			$r = ' required data-parsley-required-message="Selecione uma foto"';

			if ( !empty ( $id ) ) $r = '';
		
		?>

		<label for="capa">Capa do Quadrinho</label>
		<input type="file" name="capa" id="capa" class="form-control" accept=".jpg" <?=$r;?>>

		<input type="hidden" name="capa" value="<?=$capa;?>">

		<?php
			if ( !empty( $capa ) ) {
			
				echo "<img src='$imagem' alt='$titulo' width='80px'><br> ";
		} 
		?>

		<label for="numero">Número</label>
		<input type="text" name="numero" id="numero" required data-parsley-required-message="Preencha este campo" 
		class="form-control" value="<?= $numero ?>">

		<label for="data">Data de Lançamento</label>
		<input type="text" name="data" id="data" required data-parsley-required-message="Preencha este campo"
		 class="form-control" value="<?= $data ?>">

		<label for="valor">Valor</label>
		<input type="text" name="valor" id="valor" required data-parsley-required-message="Preencha este campo"
		 class="form-control" value="<?=$valor ?>">

		<label for="resumo">Resumo/Descrição</label>
		<textarea name="resumo" id="resumo" required data-parsley-required-message="Preencha este campo" 
		class="form-control" value=""></textarea>

		<button type="submit" class="btn btn-success margin">
			<i class="fas fa-check"></i> Gravar Dados
		</button>
	</form>

	<hr>
        <?php
        
        //verificar se esta sendo editado - include formulario personagem
        if ( !empty ( $id ) ) include "cadastro/formQuadrinho.php";
        ?>
		
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#resumo').summernote();
		$('#valor').maskMoney({
			thousands: ".",
			decimal: ","
		});
		$('#data').inputmask("99/99/9999");
		$('#numero').inputmask("9999");
	})
</script>