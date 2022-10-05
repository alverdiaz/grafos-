<?php
include("./assets/php/components/Grafo.php");
session_start();
if (!isset($_SESSION['grafo'])) {
  $_SESSION['grafo'] = new Grafo();
}

if (isset($_POST['accion'])) {
  #Eliminar vértice
  if ($_POST['accion'] == "eliminarVertice") {
    $nombre = $_POST['nombre'];
    $resultado = $_SESSION['grafo']->eliminarVertice($nombre);
    $_SESSION['resultadoEliminarVertice'] = $resultado;
  }
  #Agregar vértice
  if ($_POST['accion'] == "agregarVertice") {
    $nombre = $_POST['nombre'];
    $resultado = $_SESSION['grafo']->agregarVertice($nombre);
    $_SESSION['resultadoAgregarVertice'] = $resultado;
  }
  #Agregar arista
  if ($_POST['accion'] == "agregarArista") {
    $verticeInicial = $_POST['verticeInicial'];
    $verticeFin = $_POST['verticeFin'];
    $valor = $_POST['valor'];
    $resultado = $_SESSION['grafo']->agregarArista($verticeInicial, $verticeFin, $valor);
    $_SESSION['resultadoAgregarArista'] = $resultado;
  }
  #Eliminar arista
  if ($_POST['accion'] == "eliminarArista") {
    $verticeInicial = $_POST['verticeInicial'];
    $verticeFin = $_POST['verticeFin'];
    $resultado = $_SESSION['grafo']->eliminarArista($verticeInicial, $verticeFin);
    $_SESSION['resultadoEliminarArista'] = $resultado;
  }
  #Editar arista
  if ($_POST['accion'] == "editarArista") {
    $verticeInicial = $_POST['verticeInicial'];
    $verticeFin = $_POST['verticeFin'];
    $valor = $_POST['valor'];
    $resultado = $_SESSION['grafo']->editarArista($verticeInicial, $verticeFin, $valor);
    $_SESSION['resultadoEditarArista'] = $resultado;
  }
  #Recorrido de profundidad
  if ($_POST['accion'] == "recorridoProfundidad") {
    $nombre = $_POST['nombre'];
    $_SESSION['recorridoProfundidad'] = $_SESSION['grafo']->recorridoProfundidad($nombre);
  }
  #Recorrido de anchura
  if ($_POST['accion'] == "recorridoAnchura") {
    $nombre = $_POST['nombre'];
    $_SESSION['recorridoAnchura'] = $_SESSION['grafo']->recorridoAnchura($nombre);
  }
  #Dijkstra
  if ($_POST['accion'] == "dijkstra") {
    $verticeInicial = $_POST['verticeInicial'];
    $verticeFin = $_POST['verticeFin'];
    $resultado = $_SESSION['grafo']->dijkstra($verticeInicial, $verticeFin);
    $_SESSION['resultadoDijkstra'] = $resultado;
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Grafos</title>
  <script type="text/javascript" src="./assets/js/vis-network.min.js"></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="stylesheet" href="./assets/css/page.css">
</head>

<body>
  <div class="title">
    <h1>Grafos</h1>
  </div>
  <div class="seccion">
    <h2>Agregar vértice</h2>
    <form action="" method="POST">
      <input type="hidden" name="accion" value="agregarVertice">
      <label>Nombre</label>
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="submit">
    </form>
    <span>
      <?php
      if (isset($_SESSION['resultadoAgregarVertice'])) {
        echo ("<div class='resultado'>" . $_SESSION['resultadoAgregarVertice'] . "</div>");
        unset($_SESSION['resultadoAgregarVertice']);
      }
      ?>
    </span>
  </div>
  <div class="seccion">
    <h2>Eliminar vértice</h2>
    <?php if (!$_SESSION['grafo']->esVacio()) { ?>
      <form action="" method="POST">
        <input type="hidden" name="accion" value="eliminarVertice">
        <label>Vértice</label>
        <select name="nombre" required>
          <?php
          if (isset($_SESSION['grafo'])) {
            $vertices = $_SESSION['grafo']->getVertices();
            foreach ($vertices as $vertice) {
              echo ("<option value='" . $vertice->getNombre() . "'>" . $vertice->getNombre() . "</option>");
            }
          }
          ?>
        </select>
        <input type="submit">
      </form>
    <?php } else { ?>
      <p>El grafo está vacío</p>
    <?php } ?>
    <span>
      <?php
      if (isset($_SESSION['resultadoEliminarVertice'])) {
        echo ("<div class='resultado'>" . $_SESSION['resultadoEliminarVertice'] . "</div>");
        unset($_SESSION['resultadoEliminarVertice']);
      }
      ?>
    </span>
  </div>
  <div class="seccion">
    <h2>Agregar arista</h2>
    <?php if (!$_SESSION['grafo']->esVacio()) { ?>

      <form action="" method="POST">
        <input type="hidden" name="accion" value="agregarArista">
        <label>Vértice Inicial</label>
        <select name="verticeInicial" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <label>Vértice Final</label>
        <select name="verticeFin" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <label>Valor</label>
        <input type="number" name="valor" required>
        <input type="submit">
      </form>
    <?php } else {  ?>
      <p>El grafo está vacío</p>
    <?php } ?>
    <span>
      <?php
      if (isset($_SESSION['resultadoAgregarArista'])) {
        echo ("<div class='resultado'>" . $_SESSION['resultadoAgregarArista'] . "</div>");
        unset($_SESSION['resultadoAgregarArista']);
      }
      ?>
    </span>
  </div>
  <div class="seccion">
    <h2>Editar arista</h2>
    <?php if (!$_SESSION['grafo']->esVacio()) { ?>

      <form action="" method="POST">
        <input type="hidden" name="accion" value="editarArista">
        <label>Vértice Inicial</label>
        <select name="verticeInicial" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <label>Vértice Final</label>
        <select name="verticeFin" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <label>Valor</label>
        <input type="number" name="valor" required>
        <input type="submit">
      </form>
    <?php } else {  ?>
      <p>El grafo está vacío</p>
    <?php } ?>
    <span>
      <?php
      if (isset($_SESSION['resultadoEditarArista'])) {
        echo ("<div class='resultado'>" . $_SESSION['resultadoEditarArista'] . "</div>");
        unset($_SESSION['resultadoEditarArista']);
      }
      ?>
    </span>
  </div>
  <div class="seccion">
    <h2>Eliminar arista</h2>
    <?php if (!$_SESSION['grafo']->esVacio()) { ?>

      <form action="" method="POST">
        <input type="hidden" name="accion" value="eliminarArista">
        <label>Vértice Inicial</label>
        <select name="verticeInicial" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <label>Vértice Final</label>
        <select name="verticeFin" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <input type="submit">
      </form>
    <?php } else {  ?>
      <p>El grafo está vacío</p>
    <?php } ?>
    <span>
      <?php
      if (isset($_SESSION['resultadoEliminarArista'])) {
        echo ("<div class='resultado'>" . $_SESSION['resultadoEliminarArista'] . "</div>");
        unset($_SESSION['resultadoEliminarArista']);
      }
      ?>
    </span>
  </div>
  <div class="seccion">
    <h2>Dijkstra</h2>
    <?php if (!$_SESSION['grafo']->esVacio()) { ?>

      <form action="" method="POST">
        <input type="hidden" name="accion" value="dijkstra">
        <label>Vértice Inicial</label>
        <select name="verticeInicial" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <label>Vértice Final</label>
        <select name="verticeFin" required>
          <?php
          $vertices = $_SESSION['grafo']->getVertices();
          foreach ($vertices as $vertice) {
            echo ("<option value='" . $vertice->getId() . "'>" . $vertice->getNombre() . "</option>");
          }
          ?>
        </select>
        <input type="submit">
      </form>
    <?php } else {  ?>
      <p>El grafo está vacío</p>
    <?php } ?>
  </div>
  <div class="seccion">
    <h2>Recorrido de profundidad</h2>
    <?php if (!$_SESSION['grafo']->esVacio()) { ?>
      <form action="" method="POST">
        <input type="hidden" name="accion" value="recorridoProfundidad">
        <label>Vértice de inicio</label>
        <select name="nombre" required>
          <?php
          if (isset($_SESSION['grafo'])) {
            $vertices = $_SESSION['grafo']->getVertices();
            foreach ($vertices as $vertice) {
              echo ("<option value='" . $vertice->getNombre() . "'>" . $vertice->getNombre() . "</option>");
            }
          }
          ?>
        </select>
        <input type="submit" value="Ejecutar">
      </form>
    <?php } else { ?>
      <p>El grafo está vacío</p>
    <?php } ?>
    <span>
      <?php
      if (isset($_SESSION['recorridoProfundidad'])) {

        echo "<div id='verticesRecorridoProfundidad'>";
        foreach ($_SESSION['recorridoProfundidad'] as $vertice) {
          echo "<span>" . $vertice->getNombre() . " </span>";
        }
        echo "</div>";
        unset($_SESSION['recorridoProfundidad']);
      }
      ?>
    </span>
  </div>
  <div class="seccion">
    <h2>Recorrido de anchura</h2>
    <?php if (!$_SESSION['grafo']->esVacio()) { ?>
      <form action="" method="POST">
        <input type="hidden" name="accion" value="recorridoAnchura">
        <label>Vértice de inicio</label>
        <select name="nombre" required>
          <?php
          if (isset($_SESSION['grafo'])) {
            $vertices = $_SESSION['grafo']->getVertices();
            foreach ($vertices as $vertice) {
              echo ("<option value='" . $vertice->getNombre() . "'>" . $vertice->getNombre() . "</option>");
            }
          }
          ?>
        </select>
        <input type="submit" value="Ejecutar">
      </form>
    <?php } else { ?>
      <p>El grafo está vacío</p>
    <?php } ?>
    <span>
      <?php
      if (isset($_SESSION['recorridoAnchura'])) {

        echo "<div id='verticesRecorridoAnchura'>";
        foreach ($_SESSION['recorridoAnchura'] as $vertice) {
          echo "<span>" . $vertice->getNombre() . " </span>";
        }
        echo "</div>";
        unset($_SESSION['recorridoAnchura']);
      }
      ?>
    </span>
  </div>

  <div id="mynetwork"></div>
  <script type="text/javascript">
    // create an array with nodes
    var nodos = <?php echo ($_SESSION['grafo']->verticesAJson()); ?>;
    var nodes = new vis.DataSet(nodos);
    var edges = <?php if (isset($_SESSION['resultadoDijkstra'])) {
                  echo ($_SESSION['resultadoDijkstra']);
                  unset($_SESSION['resultadoDijkstra']);
                } else {
                  echo ($_SESSION['grafo']->aristasAJson());
                } ?>;
    // create an array with edges
    var edges = new vis.DataSet(edges);

    // create a network
    var container = document.getElementById("mynetwork");
    var data = {
      nodes: nodes,
      edges: edges,
    };
    var options = {
      edges: {

        arrows: 'to',
        scaling: {
          max: 1
        }
      }
    };
    var network = new vis.Network(container, data, options);
  </script>
</body>

</html>