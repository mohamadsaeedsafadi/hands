import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    vus: 50,
    duration: '30s',
};

export default function () {

    const payload = JSON.stringify({
        email: 'hhamode0@gmail.com',
        password: 'Password@123',
    });

    const params = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    };

    const res = http.post(
        'http://127.0.0.1:8000/api/v1/auth/user/login',
        payload,
        params
    );

    check(res, {
        'status is 200': (r) => r.status === 200,
    });

    sleep(1);
}