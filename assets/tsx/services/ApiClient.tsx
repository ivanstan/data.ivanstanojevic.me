import * as NProgress from 'react-nprogress';

export class ApiClient {

    public call(url) {
        NProgress.start();
        const globals: any = window['globals'];

        return new Promise((resolve, reject) => {
            return fetch(globals.baseUrl + url)
                .then(response => {

                    if (response.status === 200) {
                        try {
                            return response.json()
                        } catch (error) {
                            this.error(error, reject);
                        }
                    }

                    NProgress.done();
                    this.error(Error(`HTTP response ${response.status} `), resolve);
                })
                .then(data => {
                    NProgress.done();
                    resolve(data)
                })
                .catch(error => {
                    NProgress.done();
                    reject(error)
                });
        });
    }

    protected error(error, callback) {
        console.error(error);

        callback(error);
    };
}
