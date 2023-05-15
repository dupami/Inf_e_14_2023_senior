<form action="createticket.php" method="post">
    <div class="mb-2">
      <label for="cim" class="form-label">Cím</label>
      <input type="text" name="cim" id="cim" class="form-control" maxlength="50">
      </div>
      <div class="mb-3">
      <label for="hibaleiras" class="form-label">Hiba leírása</label>
      <textarea name="hibaleiras"  class="form-control"  id="hibaleiras" cols="30" rows="5" maxlength="500"></textarea>
      </div>
      <div class="mb-3">
      <label for="kontakt" class="form-label">Kontakt adatok</label>
      <textarea name="kontakt" id="kontakt" class="form-control" cols="30" rows="5" maxlength="150"></textarea>
    </div>
    <button type="submit" class="btn btn-primary" name="submitTicket">Jegy létrehozás</button>
</form>