let contador = 1;

    function agregarPregunta() {
      const contenedor = document.getElementById('contenedorPreguntas');

      const div = document.createElement('div');
      div.className = 'pregunta';
      div.innerHTML = `
        <div class="campos">
        <div class="campo">
        <label>Texto de la pregunta:</label><br>
        <input type="text" name="texto"><br>
        </div>

        
        <label>Tipo:</label>
        <select onchange="mostrarOpciones(this)" name="tipo">
          <option value="abierta">Abierta</option>
          <option value="cerrada">Cerrada</option>
          <option value="multiple">Opción múltiple</option>
        </select>
        

        <div class="opciones" style="display:none;">
          <label>Opciones:</label>
          <div class="listaOpciones"></div>
          <button type="button" onclick="agregarOpcion(this)">Agregar opción</button>
        </div>

        </div>
      `;
      div.dataset.id = contador++;
      contenedor.appendChild(div);
    }

    function mostrarOpciones(select) {
      const divOpciones = select.parentElement.querySelector('.opciones');
      if (select.value === 'cerrada' || select.value === 'multiple') {
        divOpciones.style.display = 'block';
      } else {
        divOpciones.style.display = 'none';
      }
    }

    function agregarOpcion(boton) {
      const lista = boton.parentElement.querySelector('.listaOpciones');
      const input = document.createElement('input');
      input.type = 'text';
      input.placeholder = 'Opción';
      input.className = 'opcion-input';
      lista.appendChild(input);
      lista.appendChild(document.createElement('br'));
    }

    function guardarFormulario() {
      
      const preguntasHTML = document.querySelectorAll('.pregunta');
      const preguntas = [];

      preguntasHTML.forEach((div, index) => {
        const texto = div.querySelector('input[name="texto"]').value;
        const tipo = div.querySelector('select[name="tipo"]').value;
        const pregunta = {
          id: index + 1,
          tipo: tipo,
          texto: texto
        };

        if (tipo === 'cerrada' || tipo === 'multiple') {
          const opciones = [];
          div.querySelectorAll('.listaOpciones input').forEach(input => {
            if (input.value.trim()) {
              opciones.push(input.value.trim());
            }
          });
          pregunta.opciones = opciones;
        }

        preguntas.push(pregunta);
      });

      const datos = {
        preguntas: preguntas
      };

      console.log("Datos a enviar:", datos);

      fetch('../CONTROL/CREAR_PREGUNTAS.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      })
      .then(res => res.text())
      .then(res => {
        console.log("Respuesta del servidor:", res);
        // alert("Formulario guardado con éxito.\n" + res);
      })
      .catch(err => {
        console.error("Error al enviar:", err);
        // alert("Error: " + err.message);
      });
      
    }