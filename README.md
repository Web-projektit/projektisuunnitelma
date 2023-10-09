Author: Jukka Aula.

Tässä css:ään perustuva navigointipalkki (header.php ja navbar.css), jossa on myös oma painike avatun navigointipalkin sulkemiseen.  Jälkimmäisen linkit perustuvat käyttäjän rooliin.

Molemmissa käytetään flexboxia. 

Käyttäjän rooli tallennetaan loggedIn -session-muuttujaan. Sillä voi olla arvo false,
rooli tai evästeeseen koodattu user id. 