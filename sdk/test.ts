import "dotenv/config"
import EasyMail from "./easymail";

async function main() {
    const mail = new EasyMail(process.env.EM_URL as string, process.env.EM_API_KEY as string)
        .addReceiver("jforeverything2007@gmail.com")
        .setBody("Sent Straight From Node.js SDK")
        .setSubject("Node.js Sdk Test");

    const info = await mail.send();
    console.log(info)
} main()