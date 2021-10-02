<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markerid</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.5.1/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.5.1/leaflet.css" />
    <style>
    body {padding: 0; margin: 0; }
    html, body { height: 100%; width: 100%;}
    img { border: 0;}
    #kaardikiht {float:bottom; width:100%; height:80%;} 
    </style>
    <script>
        let markerid;
        let hypik;
        let aktiivneMarker = 0;
        function algus(){
            kaart = L.map('kaardikiht').setView([59.091206, 23.950195], 9);
            new L.TileLayer(
            'https://tiles.maaamet.ee/tm/tms/1.0.0/hybriid@GMC/{z}/{x}/{y}.png&ASUTUS=TLU&ERIALA=DIGIHUMANITAARIA', {
            attribution: "Map: <a  href='http://www.maaamet.ee/'>Maa-amet</a>",
            tms: true,
        }).addTo(kaart);
        hypik=L.popup();
        kaart.on("mousemove", hiirLiigub);
        kaart.on("click", hiireVajutus);
        fetch("asukohad.txt").then(vastus => vastus.text()).then(loeMarkerid);
        }
        function loeMarkerid(andmed){
            console.log(andmed);
            markerid=[];
            let read=andmed.split("\n");
            for(let i=1; i<read.length; i++){
                let rida=read[i].split(",")
                rida.push(L.marker([rida[0], rida[1]]));
                markerid.push(rida);
                rida[4].kirjeldus=rida[3];
                rida[4].on("click", markeriVajutus)
            }
            kuvaMarkerid();
            console.log(markerid[0][4].getLatLng().distanceTo(markerid[1][4].getLatLng()))
        }
        function markeriVajutus(vajutus){
            console.log(vajutus.target);
            console.log(vajutus.target.kirjeldus);
            hypik.setLatLng(vajutus.target.getLatLng()).setContent(vajutus.target.kirjeldus).openOn(kaart);
            aktiivneMarker=vajutus.target;
        }
        function kuvaMarkerid() {
            for(let i=0; i<markerid.length; i++) {
                let m=markerid[i];
                m[4].remove();
                if(document.getElementById("cb"+m[2]).checked){
                    m[4].addTo(kaart);
                }
            }
        }
        function hiirLiigub(e){
            if(aktiivneMarker){
                kiht2.innerHTML=kauguseTekst(e.latlng.distanceTo(aktiivneMarker.getLatLng()));
            }
        }
        function kauguseTekst(kaugus){
            if(kaugus>10000){
                return (kaugus/1000).toFixed(2)+"km";
            }
                return kaugus.toFixed(2)+"m";
        }
        function hiireVajutus(e){
            tfpohjalaius.value=e.latlng.lat;
            tfidapikkus.value=e.latlng.lng;
        }
    </script>
</head>
<body onload="algus()">
    <div id="kaardikiht"></div>
    <input type="checkbox" id="cbkool" checked="checked" onchange="kuvaMarkerid()"/>Kooli kohadddd<br />
    <input type="checkbox" id="cbkodu" onchange="kuvaMarkerid()"/>Kodu kohad<br />
    <div id="kiht2"></div>
    <form action="salvestus.php">
        <input type="text" id="tfpohjalaius" name="pohjalaius" />
        <input type="text" id="tfidapikkus" name="idapikkus" />
        <input type="text" name="tyyp" placeholder="kool või kodu" />
        <input type="text" id="tfkirjeldus" name="kirjeldus">
        <input type="submit" value="salvesta">
    </form>
</body>
</html>