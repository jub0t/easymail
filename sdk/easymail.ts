import undici from 'undici'

export interface EasyMailOptions {

}

export interface EmReceiver {
    email: string,
    displayName?: string
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

    setSubject(subject: string) {
        this.message = {
            ...this.message,
            subject
        }

        return this;
    }

    async send() {
        return new Promise(async (resolve, reject) => {
            let req = await undici.request(`${this.url}/send.php`, {
                method: "POST",
                body: JSON.stringify({
                    receivers: this.receivers,
                    message: this.message,
                })
            })

            resolve(req.body)
        })
    }
}
export default EasyMail