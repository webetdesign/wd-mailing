$( document ).ready(function() {
    $("#s2id_campaign-name-select").remove();
    $("#campaign-name-select").show();

    $("#campaign-name-select").change( function() {
        $("#loadModal").modal('show');
        $.post("/MailJet/API/campaignoverview/", {
            "id": $("#campaign-name-select").val(),
        }).done(function (data) {
            data = jQuery.parseJSON(data)
            console.log(data);
            showCampaign(data);
            $("#loadModal").modal('hide');
        })
    })

});

function showCampaign(datas) {
    date = formatDateCampaign(datas[0][0]["SendEndAt"])

    info = datas[0][0];
    stat = datas[1][0];
    list = datas[2][0];

    var total = stat["Total"];

    $("#campaign-info").html('' +
        '<span style="color: #4d4b4e">Envoyé le : </span>' +
        '<span style="color: #918d92">' + date + '</span>' +
        '<span style="color: #918d92"> à ' + total + ' contact(s)</span>' +
        '<br>' +
        '<span style="color: #4d4b4e; margin-top: 15px">Objet : </span>' +
        '<span style="color: #918d92">' + info["Subject"] + '</span>' +
        '<br>' +
        '<span style="color: #4d4b4e; margin-top: 15px">Liste de contact : </span>' +
        '<span style="color: #918d92">' + list["Name"] + '</span>'
    )


    $("#campaign-taux-envoi").html('' +
        '<table class="table">\n' +
        '                        <tr>\n' +
        '                            <td class="text-center p-3" style="border-top: none;" colspan="2">\n' +
        '                                <strong style="font-size: 2rem; color: #676468">'+ total  +'</strong>\n' +
        '                                <span style="font-size: 1.5rem; color: #a9a5aa"> emails au total</span>\n' +
        '                            </td>\n' +
        '                        </tr>\n' +
        '                        <tr style="border-top: solid 1px #dbd5dc">\n' +
        '                            <td style=" width: 35%; border-top: none;" class="pt-4 text-center">\n' +
        '                                <p class="mb-0 ">\n' +
        '                                    <strong style="font-size: 2rem; color: #676468">'+ ((stat["MessageSentCount"] / total) * 100) +'% </strong>\n' +
        '                                </p>\n' +
        '                                <p class="mt-2">\n' +
        '                                    <span style="font-size: 1.5rem; color: #a9a5aa">'+  stat["MessageSentCount"] +' Délivrés</span>\n' +
        '                                </p>\n' +
        '\n' +
        '                            </td>\n' +
        '                            <td class="pt-3" style=" padding-left: 30px ;width: 65%; border-left: solid 1px #dbd5dc; border-top: none">\n' +
        '                                <p class="mb-0 ">\n' +
        '                                    <strong style="font-size: 1.5rem; color: #676468">'+ ((stat["MessageBlockedCount"] / total) * 100) +'% </strong>\n' +
        '                                    <span style="font-size: 1.3rem; color: #a9a5aa"> Bloqués</span>\n' +
        '                                </p>\n' +
        '                                <p class="mb-0 mt-2">\n' +
        '                                    <strong style="font-size: 1.5rem; color: #676468">'+ ((stat["MessageSoftBouncedCount"] / total) * 100) +'% </strong>\n' +
        '                                    <span style="font-size: 1.3rem; color: #ff624d"> Erreur</span> \n' +
        '                                </p>\n' +
        '                            </td>\n' +
        '                        </tr>\n' +
        '                    </table>'
    )


    $("#campaign-stat").html('' +
        '<table class="table" style="margin-bottom: 0"> \n' +
        '   <tr>\n' +
        '       <td class="p-5 text-center" style="border-right: solid 1px #dbd5dc">\n' +
        '           <p class="mb-0 ">\n' +
        '               <strong style="font-size: 3rem; color: #676468">'+ getStat(stat["MessageSentCount"], stat["MessageOpenedCount"]) +'%</strong>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 2rem; color: #a9a5aa">' + stat["MessageOpenedCount"] + ' </span>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 1.5rem; color: #61a810"> Ouverts</span>\n' +
        '           </p>\n' +
        '       </td>\n' +
        '       <td class="p-5 text-center" style="border-right: solid 1px #dbd5dc">\n' +
        '           <p class="mb-0 ">\n' +
        '               <strong style="font-size: 3rem; color: #676468">'+ getStat(stat["MessageClickedCount"], stat["MessageSentCount"]) +'%</strong>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 2rem; color: #a9a5aa">' + stat["MessageClickedCount"] + ' </span>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 1.5rem; color: #61a810"> Cliqués</span>\n' +
        '           </p>\n' +
        '       </td>\n' +
        '       <td class="p-5 text-center" style="border-right: solid 1px #dbd5dc">\n' +
        '           <p class="mb-0 ">\n' +
        '               <strong style="font-size: 3rem; color: #676468">'+ getStat(stat["MessageUnsubscribedCount"], stat["MessageSentCount"]) +'%</strong>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 2rem; color: #a9a5aa">' + stat["MessageUnsubscribedCount"] + ' </span>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 1.5rem; color: #2c97de"> Désabonnés</span>\n' +
        '           </p>\n' +
        '       </td>\n' +
        '       <td class="p-5 text-center" style="border-right: solid 1px #dbd5dc">\n' +
        '           <p class="mb-0 ">\n' +
        '               <strong style="font-size: 3rem; color: #676468">'+ getStat(stat["MessageSpamCount"], stat["MessageSentCount"]) +'%</strong>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 2rem; color: #a9a5aa">' + stat["MessageSpamCount"] + ' </span>\n' +
        '           </p>\n' +
        '           <p class="mt-2 mb-0">\n' +
        '               <span style="font-size: 1.5rem; color: #ff624d"> Signalé comme spam\n</span>\n' +
        '           </p>\n' +
        '       </td>\n' +
        '   </tr>\n' +
        '</table>'
    )


    $(".campaign-hidden").show();
    $("#select-campaign").removeClass("col-lg-4");
    $("#select-campaign").addClass("col-lg-2");
}

function formatDateCampaign(data){
    days = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
    months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

    date = (new Date(data));
    date_format = days[date.getDay() - 1] + " " + date.getUTCDay() + " " + months[date.getMonth() - 1] + " " + date.getFullYear();
    date_format += " à " + date.getHours() + ":" + date.getMinutes();

    return date_format;
}

function getStat($v1 , $v2){
    if ($v2 == 0){
        return 0;
    }else{
        return ($v1 / $v2) * 100;
    }
}
