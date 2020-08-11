<div class="container">
    <form action="<?php echo url_for('/staff/client/index.php'); ?>" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Choisir la Salle</label>
                    <select class="form-control form-control-sm" name="IdType">
                    <option value="">Tout</option>
                    <!-- show all salle sport -->
                    <?php $sport_set = find_all_sport();
                    while($sport = mysqli_fetch_assoc($sport_set)){    
                    $salle = find_salle_by_id($sport['IdSalle']);    
                    echo "<option value=\"" . h($sport['IdType']) . "\"";
                    if($client["IdType"] == $sport['IdType']) {
                    echo " selected";
                    }
                    echo ">" . h($salle['nom_Salle']) .' - ' . h($sport['nom_Type']) . "</option>"; 
                    }
                    mysqli_free_result($sport_set);
                    ?>
                    <!-- show all salle sport -->
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nom du client</label> <input type="text" name="nom"
                        value="<?php echo $client['nom']; ?>" class="form-control form-control-sm"
                        placeholder="Tapez le Nom">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Statue</label>
                    <select class="form-control form-control-sm" name="active">
                    <option value="">Tout</option>
                    <option value="1">Activer</option>
                    <option value="0">Desactiver</option>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
</div>