<?php
ob_start();
//ajax to chartjs
$transactions_user = get_current_transactions($_SESSION['user_id']);
$get_all_categories= get_all_categories();
// $get_all_data = get_all_data($data);
// var_dump($get_all_data);
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <div class="container justify-content-center">
    <h1 class="dashboard_title text-center pt-5">Bonjour <?= $_SESSION['user_name'] ?></h1>
    <div class="text">
    <p>Bienvenu sur <strong>Banckcount</strong> l'application  vous permet de gerer votre budget, par mois et journaliers. Remplicez le formulaire manuellement de vos dépenses ou de vos revenus en précisant la catégorie.</p>
    <p></p>
    </div>
    <div class="nav p-3 justify-content-center">
      <a href="./?page=edit-user" class="btn btn-info me-3 text-light">Profil</a>
      <a href="./?page=destroy" class="btn btn-danger text-light">Deconnection</a>
    </div>

        <div class="row justify-content-center">
            <div class="col-md-7 pt-3">
            <div class="text-success pb-2"><?php echo $resultat = "La transaction a bien été crée";?></div>
            <form action="" method="post" class="pb-3">
                  <h2 class="">Formualire de transactions</h2>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title"  name="title">
                        </div>

                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>

                        <div class="col-md-6">
                            <label for="amount" class="form-label">Coût</label>
                            <input type="text" class="form-control" id="amount" name="amount">
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="transac_type" class="form-label">Type d'opération</label>
                            <select class="form-select" id="transac_type" name="type_id">
                                <option>Choisir votre type d'opération</option>
                                <option value="2">Revenu</option>
                                <option value="1">dépense</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select" id="category" name="cat_id" disabled>
                            </select>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION["user_id"] ?>">

                        <div class="col-12 mb-3">
                            <button type="submit" name="envoyer" class="btn btn-primary">Envoyer</button>
                        </div>
                    </div>
                </form>  
            </div>
        </div>
  </div>
<div class="container-board">
  <div class="board">
  <?php foreach($transactions_user as $transaction): ?>
        <div class="<?= ($transaction['type_id'] == 1) ? 'revenu' : 'depense' ?>">
          <div class="title-board">
            <p class="text-board"><?= $transaction['cat_name']; ?></p>
            <p class="small-title-board"><?= $transaction['title'] ?></p>
          </div>
          <div class="type_board">
            <p class="text-board"><?= $transaction['type_name']; ?></p>
          </div>
          <div class="nbr-board"> 
            <p class="text-board"><?= $transaction['amount'] ?></p>
            <p class="date-board"><?= date("Y-m-d", strtotime($transaction['date'])) ?></p>
          </div>  
        </div>
        <?php endforeach; ?>
  </div>
  </div>

  <div class="row justify-content-center">
    <div class="histo col-md-6 pt-5">
                <canvas class="p-1" id="pie"></canvas>
                
    </div>
    <div class="histodeux col-md-6 pt-5">
        <canvas id="histogramme"></canvas>
    </div>
  </div>
  <script>
    const typeChoice = document.querySelector('#transac_type');

    typeChoice.addEventListener('change', function() {
        const type_id = this.value;

        fetch('?page=home', {
                method: 'POST',
                body: `type_id=${type_id}&ajax=true`,
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded',
                    'accept': 'application/json'
                }
            }).then(res => res.json())

            .then(data => {
                const categoriesOptions = document.querySelector('#category')

                categoriesOptions.removeAttribute('disabled');
                $options = '';
                data.forEach((category => {
                    $options += `<option value="${category.cat_id}">${category.cat_name}</option>`
                }))

                categoriesOptions.innerHTML = $options;
            }).catch(e => {
                console.log('====================================');
                console.log(e.message);
                console.log('====================================');
            })
    })



const userexpencesByCategorie = <?= json_encode($userexpencesByCategorie) ?>;

console.log(userexpencesByCategorie);
    

// pie

const datapie = {
  labels: userexpencesByCategorie.map(cat => cat.cat_name),
  datasets: [
    {
      label: 'Dépense du mois par categorie',
      data: userexpencesByCategorie.map(cat => cat.amount),
      backgroundColor: userexpencesByCategorie.map(cat => cat.cat_color),
    }
  ]
};
const configpie = {
  type: 'pie',
  data: datapie,
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Revenu et dépenses du mois'
      }
    }
  },
};
const myChart = new Chart(
        document.getElementById('pie'),
        configpie
    );
//FIN Pie


// Histogramme
const depenseAmountByDays = <?php echo json_encode($depenseAmountByDays) ?>;



const labelschart = [
    'Lundi',
    'Mardi',
    'Mercredi',
    'Jeudi',
    'Vendredi',
    'Samedi',
    'Dimanche'
  ];
  
  const datachart = {
    labels: labelschart,
    datasets: [{
      label: 'Dépenses journaliers',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: depenseAmountByDays,
    }]
  };


  const configchart = {
    type: 'bar',
    data: datachart,
    options: {}
  };
  const myChartchart = new Chart(
    document.getElementById('histogramme'),
    configchart
  );

//FIN Histogramme
</script>
<?php
$content = ob_get_clean();