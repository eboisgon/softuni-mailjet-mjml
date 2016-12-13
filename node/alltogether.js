var Client = require('node-rest-client').Client;
var mailjet = require ('node-mailjet')
    .connect(process.env.MJ_APIKEY_PUBLIC, process.env.MJ_APIKEY_PRIVATE)

var options_auth = { user: process.env.MJML_ID, password: process.env.MJML_PUBLIC };
var client = new Client(options_auth);
 
// PROCESSING THE MJML 
var args = {
    data: { "mjml": "<mjml>   <mj-body>     <mj-container>       <mj-section>         <mj-column>            <mj-image width=\"100\" src=\"/assets/img/logo-small.png\"></mj-image>            <mj-divider border-color=\"#F45E43\"></mj-divider>            <mj-text font-size=\"20px\" color=\"#F45E43\" font-family=\"helvetica\">Hello World</mj-text>          </mj-column>       </mj-section>     </mj-container>   </mj-body> </mjml>" },
    headers: { "Content-Type": "application/json" }
};
 
client.post("https://api.mjml.io/v1/render", args, function (data, response) {
    // SENDING THE MESSAGE 
    console.log(data);
    const request = mailjet
    .post("send")
    .request({
        "FromEmail":"sender@example.com",
        "FromName":"Mailjet Pilot",
        "Subject":"Your email flight plan!",
        "Html-part":data.html,
        "Recipients":[{"Email":"recipient@example.com"}]
    })
    request
    .then(result => {
        console.log(result.body)
    })
    .catch(err => {
        console.log(err.statusCode)
    })

});






