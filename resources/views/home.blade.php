<!DOCTYPE html>
<html>
<head>
    <title>Decision Making</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="align">

    <section class="forms-section">
        <h1 class="section-title">Prediksi STAN</h1>
        <div class="forms">
            <div class="form-wrapper is-active">
                <button type="button" class="switcher switcher-login">
                    Metode WASPAS
                    <span class="underline"></span>
                </button>
                <form class="form form-login" action="/calculate" method="POST">
                    {{ csrf_field() }}
                    <fieldset>
                        <legend>Masukkan Nilai!</legend>
                        <div class="input-block">
                            <label for="name-waspas">Name:</label>
                            <input id="name-waspas" name="name" type="text" required>
                        </div>
                        <div class="input-block">
                            <label for="twk-waspas">Nilai TWK</label>
                            <input id="twk-waspas" name="twk" type="number" required>
                        </div>
                        <div class="input-block">
                            <label for="tiu-waspas">Nilai TIU</label>
                            <input id="tiu-waspas" name="tiu" type="number" required>
                        </div>
                        <div class="input-block">
                            <label for="tkp-waspas">Nilai TKP</label>
                            <input id="tkp-waspas" name="tkp" type="number" required>
                        </div>
                        <input type="hidden" id="method-waspas" name="method" value="WASPAS">
                    </fieldset>
                    <button type="submit" class="btn-login">Hitung</button>
                </form>
            </div>
            <div class="form-wrapper">
                <button type="button" class="switcher switcher-signup">
                    Metode SAW
                    <span class="underline"></span>
                </button>
                <form class="form form-signup" action="/calculate" method="POST">
                    {{ csrf_field() }}
                    <fieldset>
                        <legend>Masukkan Nilai!</legend>
                        <div class="input-block">
                            <label for="name-saw">Name:</label>
                            <input id="name-saw" name="name" type="text" required>
                        </div>
                        <div class="input-block">
                            <label for="twk-saw">Nilai TWK</label>
                            <input id="twk-saw" name="twk" type="number" required>
                        </div>
                        <div class="input-block">
                            <label for="tiu-saw">Nilai TIU</label>
                            <input id="tiu-saw" name="tiu" type="number" required>
                        </div>
                        <div class="input-block">
                            <label for="tkp-saw">Nilai TKP</label>
                            <input id="tkp-saw" name="tkp" type="number" required>
                        </div>
                        <input type="hidden" id="method-saw" name="method" value="SAW">
                    </fieldset>
                    <button type="submit" class="btn-signup">Hitung</button>
                </form>
            </div>
        </div>
    </section>
    <script src="js/script.js"></script>

</body>

</html>