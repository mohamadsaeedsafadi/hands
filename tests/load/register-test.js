import http from 'k6/http';
import { check } from 'k6';

export const options = {
    vus: 50,
    duration: '30s',
};

export default function () {

    const unique = Math.random().toString(36).substring(2, 10);

    const payload = JSON.stringify({
        name: 'TestUser',
        email: `user_${unique}@test.com`,
        password: 'Test@1234',
        password_confirmation: 'Test@1234',
        role: 'user'
    });

    const params = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    };

    const res = http.post(
        'http://127.0.0.1:8000/api/v1/auth/user/register',
        payload,
        params
    );

    console.log(res.status);
    console.log(res.body);

    check(res, {
        'status is 201': (r) => r.status === 201,
    });
}