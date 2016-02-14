function search(searchstring)
{
    document.getElementById('searchstring').innerHTML = '';

    var elems = document.getElementsByClassName('vacancy');
    for (var i = 0; i < elems.length; i++) {
        elems[i].style.display = 'none';
    }

    var count = 0;
    for (var code in vaclist) {
        if (code.toLowerCase().search(searchstring.toLowerCase()) != -1) {
            count++;

            document.getElementById(vaclist[code]).style.display = 'block';
        }
    }
    if (!count) {
        document.getElementById('searchstring').innerHTML = 'Результатов не найдено';
    }
}
function appendElement(objId, elem)
{
    document.getElementById(objId).innerHTML = document.getElementById(objId).innerHTML +  elem;
}

window.onload = function() {
    document.getElementById('searchfield').addEventListener("keyup", function() {
        search(this.value);
    });
};
