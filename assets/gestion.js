/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/gestion.scss';

// start the Stimulus application
// import './bootstrap';

$(function() {
    console.log("%c chargement js 0.2", 'background: #222; color: #bada55');
    $("a.nav-link.ajax").on("click", function(evtClick) {
        evtClick.preventDefault();
        // console.log("click ok");
        $.ajax({
            url: $(this).prop("href"),
            dataType: "html",
            success: function(donnees) {
                $("#gestion-contenu").html(donnees);
            },
            error: function(jqXHR, statut, error) {
                console.log(jqXHR);
                $("#gestion-contenu").html("<div class='alert alert-danger'>statut : " + statut + " => erreur : " + error + "</div>");
            }
        });
    });
});