import http from 'k6/http';
import { check } from 'k6';

export const options = {

    vus: 100,

    duration: '30s',
};

const token =
    'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3YxL2F1dGgvdXNlci9sb2dpbiIsImlhdCI6MTc3ODgwMTU3MCwiZXhwIjoxNzc4ODA4NzcwLCJuYmYiOjE3Nzg4MDE1NzAsImp0aSI6Im5pbzRHRDMzbXFvQXBPZkkiLCJzdWIiOiIyMzMiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3Iiwicm9sZSI6InVzZXIiLCJndWFyZCI6InVzZXJfYXBpIn0.M9yQcYsTGV4eDezPtQ3xTYjRXlPL_T6-l1rAj4NWugw';

export default function () {

    const payload = JSON.stringify({

        category_id: 2,

        answers: {

            0: 'اختبار تنظيف',
            1: 'اختبار تنظيف',
            2: 'اختبار تنظيف',
            3: 'اختبار تنظيف',
            4: 'اختبار تنظيف',
            5: 'اختبار تنظيف',
            6: 'اختبار تنظيف',

            7: 'دمشق',

            8: 'غداً صباحاً'
        }
    });

    const params = {

        headers: {

            'Content-Type': 'application/json',

            Authorization: `Bearer ${token}`,
        },
    };

    const res = http.post(

        'http://127.0.0.1:8000/api/v1/service-requests',

        payload,

        params
    );

    check(res, {

        'status is 200': (r) => r.status === 200,

    });
}