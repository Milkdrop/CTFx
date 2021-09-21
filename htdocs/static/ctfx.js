function addLinkMouseoverSFX() {
    for (var t = document.querySelectorAll("#navbar-buttons a,#navbar-buttons button,.challenge-filename"), e = document.getElementById("audio-nav-mouseover"), n = document.getElementById("audio-nav-click"), o = 0, i = t.length; o < i; o++) {
        var a = t[o];
        if (0 == a.classList.contains("active")) {
            a.addEventListener("mouseenter", function() {
                e.currentTime = 0, e.play()
            }), a.addEventListener("click", function() {
                n.play()
            })
        }
    }
}

function addButtonMouseoverSFX() {
    var btn_dynamic_mouseover = document.getElementById("audio-btn-dynamic-mouseover");
    var btn_dynamic_click = document.getElementById("audio-btn-dynamic-click");

    var btn_solid_mouseover = document.getElementById ("audio-btn-solid-mouseover");
    var btn_solid_click = document.getElementById ("audio-btn-solid-click");

    document.querySelectorAll('.btn-dynamic').forEach((e) => {
        e.addEventListener('mouseenter', function() { btn_dynamic_mouseover.currentTime = 0; btn_dynamic_mouseover.play(); });
        e.addEventListener('mouseout', function() { btn_dynamic_mouseover.pause(); });
        e.addEventListener('click', function() { btn_dynamic_click.currentTime = 0; btn_dynamic_click.play(); });
    });

    document.querySelectorAll('.btn-solid:not(.active)').forEach((e) => {
        e.addEventListener('mouseenter', function() { btn_solid_mouseover.currentTime = 0; btn_solid_mouseover.play(); });
        e.addEventListener('mouseout', function() { btn_solid_mouseover.pause(); });
        e.addEventListener('click', function() { btn_solid_click.currentTime = 0; btn_solid_click.play(); });
    });
}

function typeWriterSFX() {
    var t = document.getElementsByClassName("typewriter")[0],
        e = document.getElementById("audio-typewriter");
    null != t && (e.play(), setTimeout(function() {
        e.pause()
    }, 300 + 1e3 / 65 * t.innerText.length))
}

function init_countdowns() {
    var countdowns = document.querySelectorAll(".countdown");
    
    setInterval(function() {
        for (countdown of countdowns) {
            var new_time = parseInt(countdown.attributes["time-difference"].value);
            if (new_time != 0) new_time -= 1;

            var approx_fun = (new_time > 0) ? Math.floor : Math.ceil;
            
            var seconds = Math.abs(new_time % 60);
            var minutes = Math.abs(approx_fun(new_time / 60) % 60);
            var hours = Math.abs(approx_fun(new_time / (60 * 60)) % 24);
            var days = Math.abs(approx_fun(new_time / (60 * 60 * 24)));
            
            var new_inner_text = "";
            if (days) new_inner_text = days + " Day" + (days==1?"":"s") + ", " + hours + " Hour" + (hours==1?"":"s");
            else if (hours) new_inner_text = hours + " Hour" + (hours==1?"":"s") + ", " + minutes + " Minute" + (minutes==1?"":"s");
            else if (minutes) new_inner_text = minutes + " Minute" + (minutes==1?"":"s") + ", " + seconds + " Second" + (seconds==1?"":"s");
            else new_inner_text = seconds + " Second" + (seconds==1?"":"s");
            
            // Lazy update
            if (new_inner_text != countdown.innerText) countdown.innerText = new_inner_text;
            countdown.attributes["time-difference"].value = new_time;
        }
    }, 1000);
}


function ctfx_assign_sfx() {
    typeWriterSFX();
    addLinkMouseoverSFX();
    addButtonMouseoverSFX();
}

init_countdowns();


ctfx_assign_sfx();