import { readFileSync } from 'fs';
import undici from 'undici'

export interface EasyMailOptions {

}

export interface EmReceiver {
    email: string,
    displayName?: string
}

export interface EmResponse {
    success: boolean,
    messages: string[]
}

export interface EmMessage {
    body?: string,
    subject?: string,
    altBody?: string
}

class EasyMail {
    private url: string;
    private api_key: string;
    private receivers: EmReceiver[] = [];
    private message?: EmMessage = {};

    constructor(url: string, api_key: string, opts?: EasyMailOptions) {
        this.url = url;
        this.api_key = api_key;
        return this;
    }

    addReceiver(email: string, displayName?: string) {
        this.receivers.push({
            email,
            displayName
        })

        return this;
    };

    setBody(body: string) {
        this.message = {
            ...this.message,
            body
        }

        return this;
    }


    setHTMLBody(file_path: string) {
        const contents = readFileSync(file_path, "utf-8");

        this.message = {
            ...this.message,
            body: contents
        }

        return this;
    }

    setSubject(subject: string) {
        this.message = {
            ...this.message,
            subject
        }

        return this;
    }

    async send(): Promise<EmResponse> {
        return new Promise(async (resolve, reject) => {
            let req = await undici.request(`${this.url}/send.php`, {
                method: "POST",
                body: JSON.stringify({
                    receivers: this.receivers,
                    message: this.message,
                }),
                headers: {
                    "api_key": this.api_key
                }
            })

            resolve(req.body.json() as any)
        })
    }
}
export default EasyMail