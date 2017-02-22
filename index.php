<?php
  $text_input = isset($_GET['text']) ? $_GET['text'] : 'Hello, world!';
  $size_input = isset($_GET['size']) ? $_GET['size'] : 20;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Genetic Algorithm</title>
    <link rel="stylesheet" href="master.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="icon" href="favicon.png" type="image/x-icon">
  </head>
  <body>
    <div class="box container">
      <h1 class="control title has-text-centered">Genetic Algorithm</h1>
      <div class="box control">
        <form>
          <p class="control">
            <label for="text" class="label">Text to reproduce</label>
            <input id="text" name="text" class="input" value="<?= $text_input; ?>" />
          </p>
          <p class="control">
            <label for="size" class="label">Population</label>
            <input type="number" min="5" max="1000" id="size" name="size" class="input" value="<?= $size_input; ?>">
            <span class="help">(Min.: 5, Max.: 1000)</span>
          </p>
          <p class="control has-text-centered">
            <button id="try-btn" class="button" type="button">Try it!</button>
          </p>
        </form>
      </div>
      <p>Looking for</p>
      <b id="looking-for" class="is-mono is-big control"><?= $text_input; ?></b>
      <p>Best estimate</p>
      <b id="best-estimate" class="is-mono is-big control">-</b>
      <hr>
      <p>Generations</p>
      <b id="generations" class="is-mono control">0</b>
      <hr>
      <section id="details" class="control">
        <div class="control">
          <button id="details-btn" class="button" type="button">Show details</button>
          <span class="help is-danger">Be careful, this might cause lag in your browser!</span>
        </div>
        <div id="details-box" class="box is-hidden">
          <table id="details-table" class="table">
            <thead>
              <tr>
                <th>Text</th>
                <th class="has-text-right">Cost</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </section>
      <div class="control">
        <button id="rerun-btn" class="button" type="button">Run again</button>
      </div>
    </div>
    <footer class="container has-text-centered">
      <span id="year">2017</span> - <small>Mihó Dániel</small>
    </footer>
    <!-- Dependencies -->
    <script src="js/gene.js"></script>
    <script src="js/population.js"></script>
    <!-- /Dependencies -->
    <script>
      var site = (function() {
        // Variables
        var population = {};
        var textInput  = document.getElementById('text');
        var sizeInput  = document.getElementById('size');
        var rerunBtn   = document.getElementById('rerun-btn');
        var tryBtn     = document.getElementById('try-btn');
        var lookingFor = document.getElementById('looking-for');
        var detailsBtn = document.getElementById('details-btn');
        var details    = { expanded: false, rows: [] };

        // Constructor
        function init() {
          populate();
          bindUIActions();
          fillDate();
        }

        // Private functions
        function populate() {
          if (population.timer) {
            clearInterval(population.timer);
          }

          population = new Population(textInput.value, sizeInput.value);
          population.generation();

          // Update looking for value
          lookingFor.textContent = textInput.value;

          // Details
          updateDetails(true);
        }

        function bindUIActions() {
          // Rerun population
          rerunBtn.addEventListener('click', populate);
          tryBtn.addEventListener('click', populate);

          // Show Gene details
          detailsBtn.addEventListener('click', function() {
            if (details.expanded === false) {
              if (confirm('This might cause lag if you set the population too high. If you want to proceed press OK.')) {
                toggleDetails();
              }
            } else {
              toggleDetails();
            }
          });
        }

        function fillDate() {
          var date = new Date().getFullYear();

          document.getElementById('year').textContent = date;
        }

        function updateDetails(init) {
          var tbody = document.getElementById('details-table').querySelector('tbody');

          if (init === true) {
            // remove existing rows
            details.rows = [];

            while (tbody.lastChild) {
              tbody.removeChild(tbody.lastChild);
            }

            for (var i = 0; i < population.members.length; i++) {
              var tr     = document.createElement('tr');
              var textTd = document.createElement('td');
              var costTd = document.createElement('td');

              tr.dataset.id = i;
              textTd.textContent = population.members[i].code;
              costTd.textContent = population.members[i].cost;
              costTd.classList.add('has-text-right');

              tr.appendChild(textTd);
              tr.appendChild(costTd);

              tbody.appendChild(tr);
              details.rows.push(tr);
            }
          }

          setInterval(function() {
            if (details.expanded === true) {
              for (var i = 0; i < details.rows.length; i++) {
                var tr = details.rows[i];

                tr.children[0].textContent = population.members[i].code;
                tr.children[1].textContent = population.members[i].cost;
              }
            }
          }, population.members.length);
        }

        function toggleDetails() {
          // Toggle button text
          var active     = detailsBtn.classList.contains('is-active');
          var detailsBox = document.getElementById('details-box');

          if (active) {
            detailsBtn.textContent = 'Show details';
          } else {
            detailsBtn.textContent = 'Hide details';
          }

          // Toggle button class, expanded var
          detailsBtn.classList.toggle('is-active');
          detailsBox.classList.toggle('is-hidden');
          details.expanded = !details.expanded;
        }

        // Start the proper function
        init();
      })();
    </script>
  </body>
</html>
