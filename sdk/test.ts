import "dotenv/config"
import EasyMail from "./easymail";

async function main() {
    const mail = new EasyMail(process.env.EM_URL as string, process.env.EM_API_KEY as string)
        .addReceiver("jforeverything2007@gmail.com")
        .setBody("This is an example")
        .setHTMLBody("./example.html") // Overwrites the raw body
        .setSubject("Node.js Sdk Test");

    const info = await mail.send();

    if (info.success) {
        console.log(`SUCCESS`, info.messages)
    } else {
        console.log(`FAILURE`, info.messages)
    }

} main()