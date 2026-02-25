<?= $this->extend('layout/login') ?>
<?= $this->section('content') ?>
<!-- PIN -->
<style>
.pin-code {
    padding: 0;
    margin: 0 auto;
    display: flex;
    justify-content: center;

}

.pin-code input {
    border: none;
    text-align: center;
    width: 50px;
    height: 60px;
    font-size: 24px;
    font-weight: bold;
    background-color: #f3f4f6;
    margin-right: 8px;
    border-radius: 12px !important;
    transition: all 0.3s;
}

.pin-code input:focus {
    background-color: #fff;
    box-shadow: 0 0 0 2px #1e40af;
    outline: none;
}


.pin-code input::-webkit-outer-spin-button,
.pin-code input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>

<div id="div_nik">
    <div class="text-center mb-5">
        <a href="<?= base_url() ?>">
            <img src="<?= base_url('assets/image/logo_opd/logokominfo-1.png') ?>" alt="logo" width="100" class="mb-4">
        </a>
        <h2 class="font-weight-bold text-dark" style="font-size: 2rem;">Masuk ke Akun</h2>
        <p class="text-muted">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
    </div>

    <form>
        <div class="form-group">
            <label for="username" class="font-weight-bold text-uppercase text-muted small">Username</label>
            <input id="username" type="text" class="form-control form-control-lg rounded-pill px-4" name="username" tabindex="1" required autofocus placeholder="Masukkan Username">
            <div class="invalid-feedback">
                Username Kosong !
            </div>
        </div>

        <div class="d-flex justify-content-center mb-3 mt-4">
            <button type="button" onclick="open_otp()" class="btn btn-primary btn-lg btn-block rounded-pill shadow-lg font-weight-bold" id="btn_otp" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); border: none;">
                Kirim Kode OTP
            </button>
            <button id="loader_otp" style="display: none;" class="btn disabled btn-primary btn-lg btn-block rounded-pill btn-progress">Loading</button>
        </div>
        
        <div class="mt-4 text-center">
            <a data-target="#exampleModalLong" data-toggle="modal" class="text-muted small" href="#exampleModalLong" style="text-decoration: underline;">
                <i class="far fa-question-circle mr-1"></i> Apa itu Id Chat?
            </a>
        </div>
    </form>
</div>

<div id="div_login" style="display:none;" class="mb-3 mt-4">
    <form>
        <div class="text-center mb-5">
            <img src="<?= base_url('assets/image/logo_opd/logokominfo-1.png') ?>" alt="logo" width="100" class="mb-4">
            <h2 class="font-weight-bold text-dark" style="font-size: 2rem;">Verifikasi OTP</h2>
            <p class="text-muted">Masukkan 6 digit kode yang dikirim ke Telegram Anda</p>
        </div>

        <div class="pin-code mb-4">
            <input type="text" id="pin_1" maxlength="1" class="form-control rounded shadow-sm mx-1" autofocus style="width: 50px; height: 60px; font-size: 24px;">
            <input type="text" id="pin_2" maxlength="1" class="form-control rounded shadow-sm mx-1" style="width: 50px; height: 60px; font-size: 24px;">
            <input type="text" id="pin_3" maxlength="1" class="form-control rounded shadow-sm mx-1" style="width: 50px; height: 60px; font-size: 24px;">
            <input type="text" id="pin_4" maxlength="1" class="form-control rounded shadow-sm mx-1" style="width: 50px; height: 60px; font-size: 24px;">
            <input type="text" id="pin_5" maxlength="1" class="form-control rounded shadow-sm mx-1" style="width: 50px; height: 60px; font-size: 24px;">
            <input type="text" id="pin_6" maxlength="1" class="form-control rounded shadow-sm mx-1" style="width: 50px; height: 60px; font-size: 24px;">
        </div>

        <center>
            <div id="timer" class="mb-4">
                <div id="countdown" class="font-weight-bold text-danger"></div>
            </div>
        </center>

        <div class="d-flex justify-content-center mb-3">
            <button id="btn_login" class="btn btn-primary btn-lg btn-block rounded-pill shadow-lg font-weight-bold" type="button" onclick="sslogin()" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); border: none;">
                Verifikasi & Masuk
            </button>
            <button id="btn_login_loader" style="display: none;" class="btn disabled btn-primary btn-lg btn-block rounded-pill btn-progress">Memproses...</button>
        </div>
        
        <div class="text-center mt-4">
            <a href="javascript:void(0)" onclick="location.reload()" class="text-muted small" style="text-decoration: underline;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
            </a>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // $('#exampleModalLong').modal("show");
});


function open_otp() {
    if (document.getElementById("username").value == "") {
        Swal.fire(
            'Gagal',
            "Username tidak boleh kosong.",
            'error'
        );
    } else if (isEmptyOrSpaces(document.getElementById("username").value)) {
        Swal.fire(
            'Gagal',
            "Username Kosong !",
            'error'
        );
    } else if (/[^a-zA-Z0-9\.@_]/.test(document.getElementById("username").value)) {
        Swal.fire(
            'Gagal',
            "Username tidak sesuai format.",
            'error'
        );
    } else {

        var otp = randomString();

        var formData = new FormData();
        formData.append('username', document.getElementById("username").value);
        formData.append('otp', otp);

        $.ajax({
            url: "<?= base_url() ?>/sslogin/get_username",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            async: true,
            dataType: "JSON",
            beforeSend: function() {
                // Loader
                document.getElementById("btn_otp").style.display = "none";
                document.getElementById("btn_otp").disabled = true;
                document.getElementById("loader_otp").style.display = "block";

            },
            success: function(data) {
                // console.log(data);
                // console.log(otp);
                if (data.status == 200) {
                    document.getElementById("div_login").style.display = "block";
                    document.getElementById("div_nik").style.display = "none";

                    // 5 minutes in seconds
                    startTimer(3 * 60);
                } else {
                    Swal.fire(
                        "Gagal",
                        data.message,
                        'error'
                    );
                    // Loader
                    document.getElementById("btn_otp").style.display = "block";
                    document.getElementById("btn_otp").disabled = false;
                    document.getElementById("loader_otp").style.display = "none";
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    'Gagal',
                    errorThrown,
                    'error'
                );
                // Loader
                document.getElementById("btn_otp").style.display = "block";
                document.getElementById("btn_otp").disabled = false;
                document.getElementById("loader_otp").style.display = "none";
            },
            complete: function() {

                // Loader
                // document.getElementById("btn_otp").style.display = "block";
                // document.getElementById("btn_otp").disabled = false;
                // document.getElementById("loader_otp").style.display = "none";
            },
        });
    }
}

function sslogin() {
    if (document.getElementById("pin_1").value == "" || document.getElementById("pin_2").value == "" || document
        .getElementById("pin_3").value == "" || document.getElementById("pin_4").value == "" || document.getElementById(
            "pin_5").value == "" || document.getElementById("pin_6").value == "") {
        Swal.fire(
            'Gagal',
            "OTP tidak boleh kosong.",
            'error'
        );
    } else {
        var pin = document.getElementById("pin_1").value + document.getElementById("pin_2").value + document
            .getElementById(
                "pin_3").value + document.getElementById("pin_4").value + document.getElementById("pin_5").value +
            document
            .getElementById("pin_6").value;

        var formData = new FormData();
        formData.append('username', document.getElementById("username").value);
        formData.append('pin', pin);

        // console.log(pin);
        $.ajax({
            url: "<?= base_url() ?>/sslogin/get_login",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            beforeSend: function() {
                // Loader
                document.getElementById("btn_login").style.display = "none";
                document.getElementById("btn_login").disabled = true;
                document.getElementById("btn_login_loader").style.display = "block";

            },
            success: function(data) {
                // console.log(data);
                if (data.status == 200) {
                    window.open("<?= base_url() ?>/admin/dashboard", "_self");
                } else {
                    Swal.fire(
                        "Gagal",
                        data.message,
                        'error'
                    );
                    // Loader
                    document.getElementById("btn_login").style.display = "block";
                    document.getElementById("btn_login").disabled = false;
                    document.getElementById("btn_login_loader").style.display = "none";
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    'Gagal',
                    errorThrown,
                    'error'
                );
                // Loader
                document.getElementById("btn_login").style.display = "block";
                document.getElementById("btn_login").disabled = false;
                document.getElementById("btn_login_loader").style.display = "none";
            },
            complete: function() {

                // // Loader
                // document.getElementById("btn_login").style.display = "block";
                // document.getElementById("btn_login").disabled = false;
                // document.getElementById("btn_login_loader").style.display = "none";
            },
        });
    }

}

function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null;
}

function randomString() {
    var length = 6;
    // var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
    return result;
}
</script>

<!-- TIMER -->
<script>
let timeInSecs;
let ticker;

function startTimer(secs) {
    timeInSecs = parseInt(secs);
    ticker = setInterval("tick()", 1000);
}

function tick() {
    let secs = timeInSecs;
    let habis = false;
    if (secs > 0) {
        timeInSecs--;
    } else {
        clearInterval(ticker);
        habis = true;
        // startTimer(5 * 60); // 5 minutes in seconds
    }

    let mins = Math.floor(secs / 60);
    secs %= 60;

    let result =
        (mins < 10 ? "0" : "") + mins + ":" + (secs < 10 ? "0" : "") + secs;

    document.getElementById("countdown").innerHTML = result;

    if (habis == true) {
        var link = '<a onclick="open_otp()"><span>Kirim Ulang OTP</span></a>';
        document.getElementById("countdown").innerHTML = link;
    }
}
</script>


<!-- PIN -->
<script>
var pinContainer = document.querySelector(".pin-code");

pinContainer.addEventListener('keyup', function(event) {
    var target = event.srcElement;

    var maxLength = parseInt(target.attributes["maxlength"].value, 10);
    var myLength = target.value.length;

    if (myLength >= maxLength) {
        var next = target;
        while (next = next.nextElementSibling) {
            if (next == null) break;
            if (next.tagName.toLowerCase() == "input") {
                next.focus();
                break;
            }
        }
    }

    if (myLength === 0) {
        var next = target;
        while (next = next.previousElementSibling) {
            if (next == null) break;
            if (next.tagName.toLowerCase() == "input") {
                next.focus();
                break;
            }
        }
    }
}, false);

pinContainer.addEventListener('keydown', function(event) {
    var target = event.srcElement;
    target.value = "";
}, false);
</script>

<?= $this->endSection() ?>