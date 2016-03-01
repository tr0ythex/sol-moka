// Adds break line after first word in advocate name
// Необходимо для унифицированного отображения ФИО (фамилия на одной строке,
// имя и отчество на второй)
var x = document.getElementsByClassName("name");
for (var i = 0; i < x.length; i++) {
    x[i].innerHTML = x[i].innerHTML.replace(' ', '<br>');
}