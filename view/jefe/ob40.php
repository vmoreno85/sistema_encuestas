<h4>Instrucciones:</h4>
<p>
  Definir como máximo 5 objetivos entre el evaluado y el jefe inmediato. Al término del año en curso se deberá calificar por parte del jefe inmediato el nivel de consecución de dichos objetivos según la siguiente escala.
</p>
<table class="table table-bordered" style="font-size: .9em;">
  <thead>
    <tr>
      <th>Calificación</th>
      <th>Definición</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>Supera ampliamente</td>
    </tr>
    <tr>
      <td>2</td>
      <td>Supera</td>
    </tr>
    <tr>
      <td>3</td>
      <td>Alcanzo objetivo</td>
    </tr>
    <tr>
      <td>4</td>
      <td>Estuvo cercade alcanzar el objetivo</td>
    </tr>
    <tr>
      <td>5</td>
      <td>No alcanzó el objetivo</td>
    </tr>
  </tbody>
</table>
<h4>Encuesta por Objetivos</h4>
<form action="" method="POST" class="form-horizontal" name="pos_op40">
  <hr>
  <p style="font-size: 1.3em;">
    Empleado: <strong><?php  echo $nombreEmp;?></strong>
  <br>
    Departamento: <strong><?php  echo $deptoEmp;?></strong>
  </p>    
  <hr>  
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Objetivos de gestión</th>
        <th>Ponderación</th>
        <th>Nivel de consecución (1 - 5)</th>
        <th>Comentarios</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <input type="text" class="form-control" name="objetivo1" placeholder=" - Objetivo 1 -" maxlength="180" required>
        </td>
        <td>
          <input type="text" class="form-control" name= "ponderacion1" onchange="SumarAutomatico(this.value);" placeholder="Valor de 0 a 100" required>
        </td>
        <td>
          <input type="text" class="form-control" name="consecucion1" placeholder=" Valor de 1 a 5 " required="">
        </td>
        <td>
          <input type="text" class="form-control" name="comentario1" placeholder=" - comentario (200 caracteres) -" maxlength="180" required>
        </td>
      </tr>  
      <tr>
        <td>
          <input type="text" class="form-control" name="objetivo2" placeholder=" - Objetivo 2 -" maxlength="180" required>
        </td>
        <td>
          <input type="text" class="form-control" name="ponderacion2" onchange="SumarAutomatico(this.value);" placeholder="Valor de 0 a 100" required>
        </td>
        <td>
          <input type="text" class="form-control" name="consecucion2" placeholder=" Valor de 1 a 5 " required>
        </td>
        <td>
          <input type="text" class="form-control" name="comentario2" placeholder=" - comentario (200 caracteres) -" maxlength="180" required>
        </td>
      </tr> 
      <tr>
        <td>
          <input type="text" class="form-control" name="objetivo3" placeholder=" - Objetivo 3 -" maxlength="180" required>
        </td>
        <td>
          <input type="text" class="form-control" name="ponderacion3" onchange="SumarAutomatico(this.value);" placeholder="Valor de 0 a 100" required>
        </td>
        <td>
          <input type="text" class="form-control" name="consecucion3" placeholder=" Valor de 1 a 5 " required>
        </td>
        <td>
          <input type="text" class="form-control" name="comentario3" placeholder=" - comentario (200 caracteres) -" maxlength="180" required>
        </td>
      </tr> 
      <tr>
        <td>
          <input type="text" class="form-control" name="objetivo4" placeholder=" - Objetivo 4 -" maxlength="180" required>
        </td>
        <td>
          <input type="text" class="form-control" name="ponderacion4" onchange="SumarAutomatico(this.value);" placeholder="Valor de 0 a 100" required>
        </td>
        <td>
          <input type="text" class="form-control" name="consecucion4" placeholder=" Valor de 1 a 5 " required>
        </td>
        <td>
          <input type="text" class="form-control" name="comentario4" placeholder=" - comentario (200 caracteres) -" maxlength="180" required>
        </td>
      </tr> 
      <tr>
        <td>
          <input type="text" class="form-control" name="objetivo5" placeholder=" - Objetivo 5 -" maxlength="180" required>
        </td>
        <td>
          <input type="text" class="form-control" name="ponderacion5" onchange="SumarAutomatico(this.value);" placeholder="Valor de 0 a 100" required>
        </td>
        <td>
          <input type="text" class="form-control" name="consecucion5" placeholder=" Valor de 1 a 5 " required>
        </td>
        <td>
          <input type="text" class="form-control" name="comentario5" placeholder=" - comentario (200 caracteres) -" maxlength="180" required>
        </td>
      </tr>    
      <tr>
        <td><span style="color:#990000; font-size: .9em;">Ponderación debe ser igual a 100</span></td>
        <td>Ponderación: <span style="color:#990000;" id="MiTotal"></span></td>
        <td></td>
        <td></td>
      </tr>                                          
    </tbody>
  </table>  
  <button type="submit" class="btn btn-success" name="btn_pos_op40">Guardar Encuesta</button>     
</form>