# API

## Client

- `/api/v1/client/auth/register` : POST method.
    - Body example:
    ```
    {
        "phone" : "375292567968",
        "password" : "123123",
        "first_name" : "Дима",
        "email" : "dima@gmail.com",
    }
    ```
    - Result:
    ```
    {
      "clientId": 1924,
      "code": "9901"
    }
    ```


- `/api/v1/client/auth/confirm-sms-code` : POST method.
    - Body example:
    ```
    {
        "phone" : "375292567968",
        "code" : "9901"
    }
    ```
    - Result:
    ```
    {
      "result": "success",
      "client": {
        "id": 1924,
        "status_id": 7,
        "first_name": "Дима",
        "middle_name": "",
        "last_name": "",
        "passport": "",
        "email": "dima@gmail.com",
        "phone": "375292567968",
        "card": null,
        "status": "active",
        "reputation": "new",
        "register": 1,
        "comment": "",
        "date_social": "2019-08-15 00:00:00",
        "order_success": 5,
        "order_error": 13,
        "created_at": "2017-09-22 01:47:42",
        "updated_at": "2019-08-20 16:04:45",
        "status_state": "new",
        "birth_day": null,
        "company_id": 1,
        "token": {
          "id": 1951,
          "driver_id": null,
          "client_id": 1924,
          "api_token": "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE",
          "created_at": "2017-09-22 01:47:42",
          "updated_at": "2017-09-22 01:47:42"
        }
      },
      "api_token": "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE"
    }
    ```
            
 - `/api/v1/client/auth/send-sms-code-reset` : POST method.
    - Body example:
        ```
        {
            "phone" : "375292567968"
        }
        ```
    - Result:
        ```
        {
          "result": "success",
          "message": "Код отправлен"
        }
        ```
                
 - `/api/v1/client/auth/confirm-sms-code-reset` : POST method.
    - Body example:
        ```
        {
            "phone" : "375292567968",
            "code" : "5850",
        }
        ```
    - Result:
        ```
        {
          "result": "success",
          "client": {
            "id": 1924,
            "status_id": 7,
            "first_name": "Дима",
            "middle_name": "",
            "last_name": "",
            "passport": "",
            "email": "dima@gmail.com",
            "phone": "375292567968",
            "card": null,
            "status": "active",
            "reputation": "new",
            "register": 1,
            "comment": "",
            "date_social": "2019-08-15 00:00:00",
            "order_success": 5,
            "order_error": 13,
            "created_at": "2017-09-22 01:47:42",
            "updated_at": "2019-08-20 16:40:54",
            "status_state": "new",
            "birth_day": null,
            "company_id": 1,
            "token": {
              "id": 1951,
              "driver_id": null,
              "client_id": 1924,
              "api_token": "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE",
              "created_at": "2017-09-22 01:47:42",
              "updated_at": "2017-09-22 01:47:42"
            }
          },
          "api_token": "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE"
        }
        ```          
        
- `/api/v1/client/auth/login` : POST method.
    - Body example:
    ```
    {
        "phone" : "375292567968",
        "password" : "123123"
    }
    ```
    - Result:
    ```
    {
      "result": "success",
      "client": {
        "id": 1924,
        "status_id": 7,
        "first_name": "test",
        "middle_name": "",
        "last_name": "",
        "passport": "",
        "email": "support@transport-manager.by",
        "phone": "375292567968",
        "card": null,
        "status": "active",
        "reputation": "new",
        "register": 1,
        "comment": "",
        "date_social": "2019-08-15 00:00:00",
        "order_success": 5,
        "order_error": 13,
        "created_at": "2017-09-22 01:47:42",
        "updated_at": "2019-08-20 15:40:31",
        "status_state": "new",
        "birth_day": null,
        "company_id": 1,
        "token": {
          "id": 1951,
          "driver_id": null,
          "client_id": 1924,
          "api_token": "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE",
          "created_at": "2017-09-22 01:47:42",
          "updated_at": "2017-09-22 01:47:42"
        }
      },
      "api_token": "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE"
    }
    ```
    
- `/api/v1/client/route/city-from` : GET method.
    - Result:
        ```
        {
          "11": "Могилёв",
          "17": "Довск",
          "20": "Гомель",
          "30": "н.п. Еремино",
          "31": "н.п. Особино",
          "32": "н.п. Октябрь",
          "33": "н.п. Заболотье",
          "34": "н.п. Дербичи",
          "35": "н.п. Антоновка",
          "36": "н.п. Меркуловичи",
          "37": "н.п. Ямное",
          "38": "н.п. Старый Довск",
          "39": "н.п. Звонец",
          "40": "н.п. Ильич",
          "41": "н.п. Веть",
          "42": "н.п. Обидовичи",
          "43": "н.п. Селец",
          "44": "н.п. Воронино",
          "45": "н.п. Годылёво",
          "46": "н.п. Сидоровичи",
          "47": "н.п. Мирный",
          "48": "Костюковка",
          "50": "н.п. Новый Кривск",
          "51": "н.п. Зелёная поляна",
          "52": "н.п. Восход",
          "53": "н.п. Рогинь",
          "55": "н.п. Следюки"
        }
        ```
- `/api/v1/client/route/city-to` : GET method.
    - Body example:
        ```
        {
            "from" : "11"
        }
        ```
    - Result:
        ```
        {
          "52": "н.п. Восход",
          "47": "н.п. Мирный",
          "46": "н.п. Сидоровичи",
          "45": "н.п. Годылёво",
          "55": "н.п. Следюки",
          "44": "н.п. Воронино",
          "43": "н.п. Селец",
          "42": "н.п. Обидовичи",
          "41": "н.п. Веть",
          "40": "н.п. Ильич",
          "39": "н.п. Звонец",
          "38": "н.п. Старый Довск",
          "17": "Довск",
          "37": "н.п. Ямное",
          "50": "н.п. Новый Кривск",
          "51": "н.п. Зелёная поляна",
          "36": "н.п. Меркуловичи",
          "35": "н.п. Антоновка",
          "34": "н.п. Дербичи",
          "53": "н.п. Рогинь",
          "33": "н.п. Заболотье",
          "32": "н.п. Октябрь",
          "31": "н.п. Особино",
          "48": "Костюковка",
          "30": "н.п. Еремино",
          "20": "Гомель"
        }
        ```
    
- `/api/v1/client/route/tours` : GET method.
    - Body example:
        ```
        {
            "from" : "11",
            "to" : "20",
            "date" : "timestamp",
            "count_places" : "1",
        }
        ```
    - Result:
        ```
        {
          "tours": [
            {
              "id": 15536,
              "schedule_id": null,
              "route_id": 22,
              "bus_id": 27,
              "bus_main_id": 27,
              "driver_id": 32,
              "date_time_start": null,
              "date_start": "2019-08-20 00:00:00",
              "time_start": "06:50:00",
              "time_finish": "09:45:00",
              "date_finish": null,
              "date_time_finish": null,
              "price": "11.50",
              "status": "active",
              "type_driver": "new",
              "type_duplicate": null,
              "shift": 0,
              "integration_id": null,
              "integration_uid": null,
              "is_reserve": 0,
              "comment": "",
              "created_at": "2019-08-07 00:31:55",
              "updated_at": "2019-08-07 00:31:55",
              "is_edit": 0,
              "reservation_by_place": 0,
              "is_collect": 0,
              "is_show_front": 1,
              "is_show_agent": 0,
              "is_rent": null,
              "rent_id": null,
              "route": {
                "id": 22,
                "name": "Могилёв-Гомель",
                "name_tr": "Могилёв-Гомель",
                "status": "active",
                "interval": 175,
                "created_at": "2017-09-12 14:53:36",
                "updated_at": "2019-05-03 21:47:31",
                "is_international": 0,
                "is_line_price": 1,
                "required_inputs": "phone,first_name",
                "is_taxi": 0,
                "is_regular": 1,
                "is_transfer": 0,
                "discount_front": 0,
                "discount_return_ticket": 0,
                "discount_child": 0,
                "bonus_agent": 0,
                "time_limit_pay": 0,
                "stations": [
                  {
                    "id": 67,
                    "city_id": 11,
                    "street_id": 3,
                    "name": "Автовокзал Могилёв",
                    "name_tr": "Автовокзал",
                    "latitude": "30.347638",
                    "longitude": "53.913116",
                    "status": "active",
                    "created_at": "2017-09-11 22:10:28",
                    "updated_at": "2018-02-02 23:14:36",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 67,
                      "order": 0,
                      "time": 0,
                      "interval": 0,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 77,
                    "city_id": 11,
                    "street_id": 2,
                    "name": "ооп пр-т Мира(Сивельга)",
                    "name_tr": "оп пр. Мира(Сивельга)",
                    "latitude": "30.346515",
                    "longitude": "53.908271",
                    "status": "active",
                    "created_at": "2017-09-12 10:24:27",
                    "updated_at": "2018-02-02 23:15:56",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 77,
                      "order": 1,
                      "time": 2,
                      "interval": 2,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 78,
                    "city_id": 11,
                    "street_id": 4,
                    "name": "ооп Электродвигатель",
                    "name_tr": "оп Электродвигатель",
                    "latitude": "30.365633",
                    "longitude": "53.905612",
                    "status": "active",
                    "created_at": "2017-09-12 10:26:50",
                    "updated_at": "2019-07-15 14:56:38",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 78,
                      "order": 2,
                      "time": 2,
                      "interval": 4,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 79,
                    "city_id": 11,
                    "street_id": 4,
                    "name": "ооп ТЦ Атлант",
                    "name_tr": "оп ТЦ Атлант",
                    "latitude": "30.367035",
                    "longitude": "53.903430",
                    "status": "active",
                    "created_at": "2017-09-12 10:29:20",
                    "updated_at": "2019-07-15 14:56:10",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 79,
                      "order": 3,
                      "time": 1,
                      "interval": 5,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 121,
                    "city_id": 11,
                    "street_id": 4,
                    "name": "ооп ул. Подгорная",
                    "name_tr": "оп ул. Подгорная",
                    "latitude": "30.368837",
                    "longitude": "53.900176",
                    "status": "active",
                    "created_at": "2017-09-12 15:03:35",
                    "updated_at": "2019-07-15 14:56:28",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 121,
                      "order": 4,
                      "time": 1,
                      "interval": 6,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 80,
                    "city_id": 11,
                    "street_id": 4,
                    "name": "ооп мик-н Фатина мост",
                    "name_tr": "оп мик-н Фатина мост",
                    "latitude": "30.381968",
                    "longitude": "53.880997",
                    "status": "active",
                    "created_at": "2017-09-12 10:31:42",
                    "updated_at": "2019-07-15 14:55:28",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 80,
                      "order": 5,
                      "time": 2,
                      "interval": 8,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 81,
                    "city_id": 11,
                    "street_id": 5,
                    "name": "ооп ул. Залуцкого",
                    "name_tr": "оп ул. Залуцкого",
                    "latitude": "30.376850",
                    "longitude": "53.875105",
                    "status": "active",
                    "created_at": "2017-09-12 10:35:36",
                    "updated_at": "2019-07-15 14:56:19",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 81,
                      "order": 6,
                      "time": 1,
                      "interval": 9,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 82,
                    "city_id": 11,
                    "street_id": 5,
                    "name": "ооп пр-т Димитрова",
                    "name_tr": "оп пр-т Димитрова",
                    "latitude": "30.369011",
                    "longitude": "53.870680",
                    "status": "active",
                    "created_at": "2017-09-12 10:39:08",
                    "updated_at": "2019-07-15 14:55:59",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 82,
                      "order": 7,
                      "time": 1,
                      "interval": 10,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 83,
                    "city_id": 11,
                    "street_id": 6,
                    "name": "ооп Могилевоблавтотранс (кольцо)",
                    "name_tr": "оп МогОблавтотранс(кольцо)",
                    "latitude": "30.357561",
                    "longitude": "53.863857",
                    "status": "active",
                    "created_at": "2017-09-12 10:40:39",
                    "updated_at": "2019-07-15 14:55:40",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 83,
                      "order": 8,
                      "time": 1,
                      "interval": 11,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 84,
                    "city_id": 11,
                    "street_id": 6,
                    "name": "ооп Завод Красный Металист",
                    "name_tr": "оп Завод Красный Металист",
                    "latitude": "30.358592",
                    "longitude": "53.858149",
                    "status": "active",
                    "created_at": "2017-09-12 10:44:41",
                    "updated_at": "2019-07-15 14:55:18",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 84,
                      "order": 9,
                      "time": 1,
                      "interval": 12,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 85,
                    "city_id": 52,
                    "street_id": 38,
                    "name": "ост. поворот на Восход",
                    "name_tr": "ост. поворот на Восход",
                    "latitude": "30.363648",
                    "longitude": "53.775680",
                    "status": "active",
                    "created_at": "2017-09-12 10:48:52",
                    "updated_at": "2018-05-04 10:14:49",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 85,
                      "order": 10,
                      "time": 1,
                      "interval": 13,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 86,
                    "city_id": 47,
                    "street_id": 33,
                    "name": "ост. Мирный",
                    "name_tr": "ост. Мирный",
                    "latitude": "30.365177",
                    "longitude": "53.755902",
                    "status": "active",
                    "created_at": "2017-09-12 10:52:00",
                    "updated_at": "2018-05-03 16:58:03",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 86,
                      "order": 11,
                      "time": 1,
                      "interval": 14,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 87,
                    "city_id": 46,
                    "street_id": 32,
                    "name": "поворот на Сидоровичи",
                    "name_tr": "поворот на Сидоровичи",
                    "latitude": "30.369741",
                    "longitude": "53.683929",
                    "status": "active",
                    "created_at": "2017-09-12 10:58:44",
                    "updated_at": "2018-05-03 16:57:42",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 87,
                      "order": 12,
                      "time": 18,
                      "interval": 32,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 88,
                    "city_id": 45,
                    "street_id": 31,
                    "name": "поворот на Годылёво",
                    "name_tr": "поворот на Годылёво",
                    "latitude": "30.349028",
                    "longitude": "53.617151",
                    "status": "active",
                    "created_at": "2017-09-12 11:00:04",
                    "updated_at": "2018-05-03 16:57:09",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 88,
                      "order": 13,
                      "time": 6,
                      "interval": 38,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 129,
                    "city_id": 55,
                    "street_id": 40,
                    "name": "ост. Следюки",
                    "name_tr": "Следюки",
                    "latitude": "30.337846",
                    "longitude": "53.577470",
                    "status": "active",
                    "created_at": "2018-10-20 20:44:43",
                    "updated_at": "2018-10-20 20:48:49",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 129,
                      "order": 14,
                      "time": 2,
                      "interval": 40,
                      "cost_start": -0.53,
                      "cost_finish": -8.47
                    }
                  },
                  {
                    "id": 69,
                    "city_id": 44,
                    "street_id": 30,
                    "name": "ост. Воронино",
                    "name_tr": "ост. Воронино",
                    "latitude": "30.343045",
                    "longitude": "53.530845",
                    "status": "active",
                    "created_at": "2017-09-11 22:15:17",
                    "updated_at": "2018-05-03 16:56:49",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 69,
                      "order": 15,
                      "time": 2,
                      "interval": 42,
                      "cost_start": -0.92,
                      "cost_finish": -8.08
                    }
                  },
                  {
                    "id": 70,
                    "city_id": 43,
                    "street_id": 29,
                    "name": "ост. Селец",
                    "name_tr": "ост. Селец",
                    "latitude": "30.393407",
                    "longitude": "53.386279",
                    "status": "active",
                    "created_at": "2017-09-11 22:20:25",
                    "updated_at": "2018-05-03 16:56:25",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 70,
                      "order": 16,
                      "time": 10,
                      "interval": 52,
                      "cost_start": -2.24,
                      "cost_finish": -6.76
                    }
                  },
                  {
                    "id": 71,
                    "city_id": 42,
                    "street_id": 28,
                    "name": "ост. Обидовичи",
                    "name_tr": "ост. Обидовичи",
                    "latitude": "30.400101",
                    "longitude": "53.339444",
                    "status": "active",
                    "created_at": "2017-09-11 22:21:20",
                    "updated_at": "2018-05-03 16:55:59",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 71,
                      "order": 17,
                      "time": 4,
                      "interval": 56,
                      "cost_start": -2.71,
                      "cost_finish": -6.29
                    }
                  },
                  {
                    "id": 72,
                    "city_id": 41,
                    "street_id": 27,
                    "name": "ост. Веть",
                    "name_tr": "ост. Веть",
                    "latitude": "30.411778",
                    "longitude": "53.295693",
                    "status": "active",
                    "created_at": "2017-09-11 22:22:01",
                    "updated_at": "2018-05-03 16:55:34",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 72,
                      "order": 18,
                      "time": 3,
                      "interval": 59,
                      "cost_start": -2.94,
                      "cost_finish": -6.06
                    }
                  },
                  {
                    "id": 73,
                    "city_id": 40,
                    "street_id": 26,
                    "name": "ост. Ильич",
                    "name_tr": "ост. Ильич",
                    "latitude": "30.423269",
                    "longitude": "53.262656",
                    "status": "active",
                    "created_at": "2017-09-11 22:23:43",
                    "updated_at": "2018-05-03 16:55:08",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 73,
                      "order": 19,
                      "time": 2,
                      "interval": 61,
                      "cost_start": -3.41,
                      "cost_finish": -6.29
                    }
                  },
                  {
                    "id": 90,
                    "city_id": 39,
                    "street_id": 25,
                    "name": "ост. Звонец",
                    "name_tr": "ост. Звонец",
                    "latitude": "30.432688",
                    "longitude": "53.236487",
                    "status": "active",
                    "created_at": "2017-09-12 11:05:53",
                    "updated_at": "2018-05-03 16:54:42",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 90,
                      "order": 20,
                      "time": 2,
                      "interval": 63,
                      "cost_start": -3.64,
                      "cost_finish": -5.36
                    }
                  },
                  {
                    "id": 91,
                    "city_id": 38,
                    "street_id": 24,
                    "name": "ост. Старый Довск",
                    "name_tr": "ост. Старый Довск",
                    "latitude": "30.448376",
                    "longitude": "53.191140",
                    "status": "active",
                    "created_at": "2017-09-12 12:32:17",
                    "updated_at": "2018-05-03 16:54:14",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 91,
                      "order": 21,
                      "time": 7,
                      "interval": 70,
                      "cost_start": -3.8,
                      "cost_finish": -5.2
                    }
                  },
                  {
                    "id": 74,
                    "city_id": 17,
                    "street_id": 14,
                    "name": "Автовокзал Довск",
                    "name_tr": "Автовокзал Довск",
                    "latitude": "30.459249",
                    "longitude": "53.156167",
                    "status": "active",
                    "created_at": "2017-09-11 22:24:37",
                    "updated_at": "2018-02-02 23:39:57",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 74,
                      "order": 22,
                      "time": 9,
                      "interval": 79,
                      "cost_start": -4.34,
                      "cost_finish": -4.66
                    }
                  },
                  {
                    "id": 92,
                    "city_id": 37,
                    "street_id": 23,
                    "name": "ост. Ямное",
                    "name_tr": "ост. Ямное",
                    "latitude": "30.479176",
                    "longitude": "53.132883",
                    "status": "active",
                    "created_at": "2017-09-12 12:33:38",
                    "updated_at": "2018-05-03 16:53:34",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 92,
                      "order": 23,
                      "time": 4,
                      "interval": 83,
                      "cost_start": -6.92,
                      "cost_finish": -4.58
                    }
                  },
                  {
                    "id": 94,
                    "city_id": 50,
                    "street_id": 36,
                    "name": "ост. Новый Кривск",
                    "name_tr": "Новый Кривск",
                    "latitude": "30.523893",
                    "longitude": "53.077265",
                    "status": "active",
                    "created_at": "2017-09-12 12:35:05",
                    "updated_at": "2018-05-04 10:30:55",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 94,
                      "order": 24,
                      "time": 3,
                      "interval": 86,
                      "cost_start": -5.04,
                      "cost_finish": -3.96
                    }
                  },
                  {
                    "id": 95,
                    "city_id": 51,
                    "street_id": 35,
                    "name": "ост. Зелёная поляна",
                    "name_tr": "Зелёная поляна",
                    "latitude": "30.549391",
                    "longitude": "53.044896",
                    "status": "active",
                    "created_at": "2017-09-12 12:35:46",
                    "updated_at": "2018-05-04 10:11:32",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 95,
                      "order": 25,
                      "time": 3,
                      "interval": 89,
                      "cost_start": -5.04,
                      "cost_finish": -3.96
                    }
                  },
                  {
                    "id": 75,
                    "city_id": 36,
                    "street_id": 22,
                    "name": "ост. Меркуловичи",
                    "name_tr": "ост. Меркуловичи",
                    "latitude": "30.604177",
                    "longitude": "52.974791",
                    "status": "active",
                    "created_at": "2017-09-11 22:25:50",
                    "updated_at": "2018-05-03 16:53:05",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 75,
                      "order": 26,
                      "time": 7,
                      "interval": 96,
                      "cost_start": -6.13,
                      "cost_finish": -2.87
                    }
                  },
                  {
                    "id": 76,
                    "city_id": 35,
                    "street_id": 21,
                    "name": "ост. Антоновка",
                    "name_tr": "ост. Антоновка",
                    "latitude": "30.651656",
                    "longitude": "52.911913",
                    "status": "active",
                    "created_at": "2017-09-11 22:27:31",
                    "updated_at": "2018-05-03 16:52:33",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 76,
                      "order": 27,
                      "time": 10,
                      "interval": 106,
                      "cost_start": -7.15,
                      "cost_finish": -2.25
                    }
                  },
                  {
                    "id": 100,
                    "city_id": 34,
                    "street_id": 20,
                    "name": "ост. Дербичи",
                    "name_tr": "ост. Дербичи",
                    "latitude": "30.666135",
                    "longitude": "52.892640",
                    "status": "active",
                    "created_at": "2017-09-12 12:44:11",
                    "updated_at": "2018-05-03 16:52:07",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 100,
                      "order": 28,
                      "time": 2,
                      "interval": 108,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 101,
                    "city_id": 53,
                    "street_id": 39,
                    "name": "ост. поворот на Рогинь",
                    "name_tr": "ост. поворот на Рогинь",
                    "latitude": "30.683247",
                    "longitude": "52.870029",
                    "status": "active",
                    "created_at": "2017-09-12 12:45:04",
                    "updated_at": "2018-05-04 10:16:01",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 101,
                      "order": 29,
                      "time": 2,
                      "interval": 110,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 102,
                    "city_id": 33,
                    "street_id": 19,
                    "name": "ост. Заболотье",
                    "name_tr": "ост. Заболотье",
                    "latitude": "30.724885",
                    "longitude": "52.814706",
                    "status": "active",
                    "created_at": "2017-09-12 12:45:43",
                    "updated_at": "2018-05-03 16:51:43",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 102,
                      "order": 30,
                      "time": 5,
                      "interval": 115,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 103,
                    "city_id": 32,
                    "street_id": 18,
                    "name": "ост. Октябрь",
                    "name_tr": "ост. Октябрь",
                    "latitude": "30.827159",
                    "longitude": "52.659589",
                    "status": "active",
                    "created_at": "2017-09-12 12:46:25",
                    "updated_at": "2018-06-02 14:51:44",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 103,
                      "order": 31,
                      "time": 15,
                      "interval": 130,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 104,
                    "city_id": 31,
                    "street_id": 17,
                    "name": "ост. Особино",
                    "name_tr": "ост. Особино",
                    "latitude": "30.843706",
                    "longitude": "52.630154",
                    "status": "active",
                    "created_at": "2017-09-12 12:46:53",
                    "updated_at": "2018-05-03 16:33:38",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 104,
                      "order": 32,
                      "time": 3,
                      "interval": 133,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 107,
                    "city_id": 48,
                    "street_id": 34,
                    "name": "Костюковка",
                    "name_tr": "Костюковка",
                    "latitude": "30.912411",
                    "longitude": "52.540247",
                    "status": "active",
                    "created_at": "2017-09-12 12:49:43",
                    "updated_at": "2018-05-03 22:29:39",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 107,
                      "order": 33,
                      "time": 15,
                      "interval": 148,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 108,
                    "city_id": 30,
                    "street_id": 16,
                    "name": "ост. Еремино (школа)",
                    "name_tr": "ост. Еремино (школа)",
                    "latitude": "30.944814",
                    "longitude": "52.508307",
                    "status": "active",
                    "created_at": "2017-09-12 12:52:08",
                    "updated_at": "2018-05-03 16:59:21",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 108,
                      "order": 34,
                      "time": 3,
                      "interval": 151,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 109,
                    "city_id": 30,
                    "street_id": 16,
                    "name": "ост. Еремино (заправка)",
                    "name_tr": "ост. Еремино (заправка)",
                    "latitude": "30.965146",
                    "longitude": "52.489232",
                    "status": "active",
                    "created_at": "2017-09-12 12:54:48",
                    "updated_at": "2018-05-03 16:59:00",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 109,
                      "order": 35,
                      "time": 3,
                      "interval": 154,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 110,
                    "city_id": 20,
                    "street_id": 9,
                    "name": "ооп Гидропривод",
                    "name_tr": "ооп Гидропривод",
                    "latitude": "31.003259",
                    "longitude": "52.440851",
                    "status": "active",
                    "created_at": "2017-09-12 12:57:56",
                    "updated_at": "2018-02-02 23:28:51",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 110,
                      "order": 36,
                      "time": 10,
                      "interval": 164,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 111,
                    "city_id": 20,
                    "street_id": 9,
                    "name": "ооп Горэлектротранспорт",
                    "name_tr": "ооп Горэлектротранспорт",
                    "latitude": "30.983700",
                    "longitude": "52.465776",
                    "status": "active",
                    "created_at": "2017-09-12 13:01:23",
                    "updated_at": "2018-02-02 23:30:44",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 111,
                      "order": 37,
                      "time": 1,
                      "interval": 165,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 112,
                    "city_id": 20,
                    "street_id": 12,
                    "name": "ооп ул. Чонгарской Дивизии",
                    "name_tr": "ооп ул. Чонгарской Дивизии",
                    "latitude": "30.986632",
                    "longitude": "52.462177",
                    "status": "active",
                    "created_at": "2017-09-12 13:35:39",
                    "updated_at": "2018-02-02 23:32:23",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 112,
                      "order": 38,
                      "time": 1,
                      "interval": 166,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 113,
                    "city_id": 20,
                    "street_id": 11,
                    "name": "ооп ул. Тимофеенко",
                    "name_tr": "ооп ул.Тимофеенко",
                    "latitude": "30.991618",
                    "longitude": "52.455906",
                    "status": "active",
                    "created_at": "2017-09-12 13:39:09",
                    "updated_at": "2018-02-02 23:32:13",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 113,
                      "order": 39,
                      "time": 1,
                      "interval": 167,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 114,
                    "city_id": 20,
                    "street_id": 13,
                    "name": "опп РемБытТехника",
                    "name_tr": "опп РемБытТехника",
                    "latitude": "30.994704",
                    "longitude": "52.451825",
                    "status": "active",
                    "created_at": "2017-09-12 13:41:57",
                    "updated_at": "2018-02-02 23:37:18",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 114,
                      "order": 40,
                      "time": 1,
                      "interval": 168,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 115,
                    "city_id": 20,
                    "street_id": 9,
                    "name": "ооп Ун-т Ф. Скорины",
                    "name_tr": "ооп Университет Скорины",
                    "latitude": "31.002034",
                    "longitude": "52.442485",
                    "status": "active",
                    "created_at": "2017-09-12 13:50:11",
                    "updated_at": "2018-02-02 23:33:10",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 115,
                      "order": 41,
                      "time": 1,
                      "interval": 169,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 116,
                    "city_id": 20,
                    "street_id": 9,
                    "name": "ооп Универмаг Гомель",
                    "name_tr": "ооп Универмаг",
                    "latitude": "31.004240",
                    "longitude": "52.439731",
                    "status": "active",
                    "created_at": "2017-09-12 13:54:05",
                    "updated_at": "2018-02-02 23:33:39",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 116,
                      "order": 42,
                      "time": 1,
                      "interval": 170,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 117,
                    "city_id": 20,
                    "street_id": 9,
                    "name": "ооп Фабрика 8 марта",
                    "name_tr": "ооп Фабрика 8 марта",
                    "latitude": "31.007382",
                    "longitude": "52.435849",
                    "status": "active",
                    "created_at": "2017-09-12 14:09:07",
                    "updated_at": "2018-02-02 23:34:58",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 117,
                      "order": 43,
                      "time": 1,
                      "interval": 171,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 118,
                    "city_id": 20,
                    "street_id": 9,
                    "name": "ооп БелГУТ",
                    "name_tr": "ооп БелГУТ",
                    "latitude": "31.001274",
                    "longitude": "52.433781",
                    "status": "active",
                    "created_at": "2017-09-12 14:21:45",
                    "updated_at": "2018-02-02 23:26:15",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 118,
                      "order": 44,
                      "time": 2,
                      "interval": 173,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 123,
                    "city_id": 20,
                    "street_id": 8,
                    "name": "ЖД Вокзал(Курчатова)",
                    "name_tr": "ЖД Вокзал(Курчатова)",
                    "latitude": "30.993405",
                    "longitude": "52.432413",
                    "status": "active",
                    "created_at": "2017-09-30 20:57:24",
                    "updated_at": "2019-01-02 21:18:38",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 123,
                      "order": 45,
                      "time": 1,
                      "interval": 174,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  },
                  {
                    "id": 68,
                    "city_id": 20,
                    "street_id": 7,
                    "name": "Автовокзал Гомель",
                    "name_tr": "Автовокзал",
                    "latitude": "30.993209",
                    "longitude": "52.434151",
                    "status": "active",
                    "created_at": "2017-09-11 22:13:35",
                    "updated_at": "2018-02-02 23:20:43",
                    "pivot": {
                      "route_id": 22,
                      "station_id": 68,
                      "order": 46,
                      "time": 1,
                      "interval": 175,
                      "cost_start": 0,
                      "cost_finish": 0
                    }
                  }
                ]
              },
              "bus": {
                "id": 27,
                "company_id": 1,
                "template_id": 29,
                "name": "New VW Crafter (20 мест, тёмно-зеленый)",
                "name_tr": "Crafter",
                "number": "6TAX6163",
                "places": 20,
                "status": "active",
                "comment": "",
                "garage_longitude": null,
                "garage_latitude": null,
                "universal_field": null,
                "universal_day": null,
                "is_rent": 0,
                "created_at": "2018-12-09 22:18:21",
                "updated_at": "2018-12-28 12:08:13",
                "insurance_day": "2018-12-09",
                "revision_day": "2018-12-09",
                "bus_type_id": 2
              }
            }
          ]
        }
        ```


- `/api/v1/client/order/list` : GET method.
    - Body example:
    ```
    {
        "api_token" : "lj3TEGUPYXQoiF00x4YKJsBpmNdiG8P1",
    }
    ```
    - Result:
    ```
    {
      "client": {
        "id": 1924,
        "status_id": 7,
        "first_name": "Дима",
        "middle_name": "",
        "last_name": "",
        "passport": "",
        "email": "dima@gmail.com",
        "phone": "375292567968",
        "card": null,
        "status": "active",
        "reputation": "new",
        "register": 1,
        "comment": "",
        "date_social": "2019-08-15 00:00:00",
        "order_success": 5,
        "order_error": 13,
        "created_at": "2017-09-22 01:47:42",
        "updated_at": "2019-08-20 16:40:54",
        "status_state": "new",
        "birth_day": null,
        "company_id": 1,
        "token": {
          "id": 1951,
          "driver_id": null,
          "client_id": 1924,
          "api_token": "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE",
          "created_at": "2017-09-22 01:47:42",
          "updated_at": "2017-09-22 01:47:42"
        }
      },
      "orders": [
        {
          "id": 43388,
          "uid": null,
          "tour_id": 15423,
          "client_id": 1924,
          "operator_id": null,
          "coupon_id": null,
          "station_from_id": 67,
          "station_to_id": 68,
          "price": "11.50",
          "is_return_ticket": 0,
          "count_places": 1,
          "status": "disable",
          "type": "no_completed",
          "pay_id": null,
          "pay_url": null,
          "type_pay": "",
          "source": "",
          "confirm": 1,
          "pull": 0,
          "comment": "",
          "old_places": null,
          "social_status_confirm": null,
          "places_with_number": 0,
          "appearance": null,
          "station_from_time": "09:30:00",
          "station_to_time": "12:25:00",
          "created_at": "2019-08-07 10:50:19",
          "updated_at": "2019-08-14 10:00:19",
          "count_places_appearance": 0,
          "count_places_no_appearance": 0,
          "is_call": 0,
          "cnt_sms": 0,
          "is_pay": 0,
          "created_user_id": null,
          "modified_user_id": null,
          "canceled_user_id": null,
          "order_places": [
            {
              "id": 68872,
              "order_id": 43388,
              "status_id": null,
              "number": "11",
              "price": "11.50",
              "is_handler_price": 0,
              "is_return_ticket": 0,
              "status_old_price": "0.00",
              "created_at": "2019-08-07 10:50:20",
              "updated_at": "2019-08-07 10:50:20",
              "appearance": null,
              "is_child": null,
              "passport": null,
              "name": null,
              "surname": null,
              "patronymic": null,
              "card": null,
              "birth_day": null,
              "station_from_id": 67,
              "station_to_id": 68,
              "start_min": 0,
              "finish_min": 175,
              "url": null
            }
          ],
          "station_from": {
            "id": 67,
            "city_id": 11,
            "street_id": 3,
            "name": "Автовокзал Могилёв",
            "name_tr": "Автовокзал",
            "latitude": "30.347638",
            "longitude": "53.913116",
            "status": "active",
            "created_at": "2017-09-11 22:10:28",
            "updated_at": "2018-02-02 23:14:36",
            "city": {
              "id": 11,
              "name": "Могилёв",
              "name_tr": "Могилёв",
              "timezone": "Europe\/Minsk",
              "status": "active",
              "is_rent": 0,
              "is_transfer": 0,
              "created_at": "2017-09-11 22:05:34",
              "updated_at": "2017-09-11 22:05:34"
            }
          },
          "station_to": {
            "id": 68,
            "city_id": 20,
            "street_id": 7,
            "name": "Автовокзал Гомель",
            "name_tr": "Автовокзал",
            "latitude": "30.993209",
            "longitude": "52.434151",
            "status": "active",
            "created_at": "2017-09-11 22:13:35",
            "updated_at": "2018-02-02 23:20:43",
            "city": {
              "id": 20,
              "name": "Гомель",
              "name_tr": "Гомель",
              "timezone": "Europe\/Minsk",
              "status": "active",
              "is_rent": 0,
              "is_transfer": 0,
              "created_at": "2017-09-11 22:08:23",
              "updated_at": "2017-09-11 22:08:23"
            }
          }
        }
      ]
    }
    ```


- `/api/v1/client/order/cancel` : POST method.
  - Body example:
    ```
    {
        "order_id" : "43899",
        "api_token" : "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE"
    }
    ```
  - Result:
    ```
    {
      "result": "success"
    }
    ```

- `/api/v1/client/order/add ` : POST method.
  - Body example:
    ```
    {
        "count_places" : 2,
        "api_token" : "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE",
        "station_from_id" : "67",
        "station_to_id" : "108",
        "tour_id" : "15546"
    }
    ```
    - Result:
    ```
    {
      "result": "success"
    }
    ```
    
- `/api/v1/client/info ` : GET method.
    - Body example:
    ```
    {
        "api_token" : "BR52b04cAgqXlmhv8HEIv0WcjJANdcTE"
    }
    ```
    - Result:
    ```
    {
      "id": 1,
      "status_id": null,
      "first_name": "Test",
      "middle_name": "Test",
      "last_name": "Test",
      "passport": "Test",
      "email": "ivan.zhibul@gmail.com",
      "phone": "375291667405",
      "card": null,
      "status": "active",
      "reputation": "new",
      "register": 0,
      "comment": "",
      "date_social": null,
      "order_success": 0,
      "order_error": 0,
      "created_at": "2019-05-18 00:52:10",
      "updated_at": "2019-05-18 00:55:31",
      "status_state": "",
      "birth_day": null,
      "company_id": null
    }
    ```    
    
## Driver

- `/api/v1/driver/auth/login` : POST method.
  - Body example:
    ```
    {
        "phone" : "375447622173",
        "password" : "test"
    }
    ```
  - Result:
    ```
    {
        "result" : "success",
        "api_token" : "321!@#dfd#@$32"
    }
    ```

- `/api/v1/driver/tours/stations` : POST method.
  - Body example:
    ```
    {
        "tour_id" : 11,
    }
    ```
  - Result
    ```
    {
        "result": "success",
        "stations": [
            {"id": 1, "name": "Минск Вокзал"},
        ]
    }
    ```

- `/api/v1/driver/tours` : GET method.
  - Body example:
    ```
    {
        "date_from" : "timestamp" || 'current day',
        "date_to" : "timestamp" || 'current day'
    }
    ```
  - Result:
    ```
    {
        "result" : "success",
        "tours" : [
            {
                "id": "1",
                "type_driver": "new" || "collection" || "collection_end" || "way" || "completed",
                "route": "Минск-Борисов",
                "date": "5 ноября, 16:00",
                "bus": "4444,Mersedes Bens",
                "bus_places": "7",
                "clients_count": "4"
            }
        ]
    }
    ```
- `/api/v1/driver/tours/{tourId}` : GET method.
error: if (tour.type_driver == 'new')
  - Result:
    ```
    {
        "result" : "success",
        "tours" : [
            {
                "id": "1",
                "route": "Минск-Борисов",
                "date": "5 ноября, 16:00",
                "price": "55",
                "clients_count": "7",
                "orders": [
                    {
                    "id": "1",
                    "place_id": "1",
                    "appearance": null,
                    "client_name": "Егор",
                    "client_phone": "+375 (44) 997-55-85",
                    "client_status": "",
                    "client_status_image": (path) || null,
                    "client_status_state": '' || 'new' || 'driver_ok' || 'operator_ok',
                    "price": "10.00",
                    "number": "A1",
                    "appearance": null, (0 || 1),
                    "comment": "Комментарий о заказе",
                    "station_from_name": "Минск",
                    "station_to_name": "Витебск"
                    }
                ],
                "stations_from": [
                    "station_name": "Минск",
                    "station_time": "16:00",
                    "orders": [
                        {
                        "id": "1",
                        "client_name": "Егор",
                        "client_phone": "+375 (44) 997-55-85",
                        "places": "1,2",
                        }
                    ]
                ],
                "stations_to": [
                    "station_name": "Витебск",
                    "station_time": "17:00",
                    "orders": [
                        {
                        "id": "1",
                        "client_name": "Егор",
                        "client_phone": "+375 (44) 997-55-85",
                        "places": "1,2",
                        }
                    ]
                ],
            }
        ]
    }
    ```
- `/api/v1/driver/tours/store` : POST method.
error: if (tour.time < 2h)
  - Body:
    ```
    {
        "id": "1",
        "type_driver": "new" || "collection" || "way" || "completed"
    }
    ```
- `/api/v1/driver/orders/{orderId}` : GET method.
  - Result:
    ```
    {
        "result" : "success",
        "order" : {
            "client_id": "1",
            "client_name": "Егор",
            "client_phone": "+375 44 7621-21-73",
            "client_status": "Студент" || null,
            "client_status_image": "http://vimi.by/uploads/.../*.jpg" || null,
            "client_status_state": '' || 'new' || 'driver_ok' || 'operator_ok',
            "price": "7",
            "comment": "Комментарий"
        }
    }
    ```
- `/api/v1/driver/orders/add` : POST method.
  - Body:
    ```
    {
        "phone": "+375447622173",
        "first_name": "Egor",
        "status_id": null,
        "tour_id": 11,
        "places": [{"A1", "A2"}],
        "station_from_id": 1,
        "station_to_id": 15,
    }
    ```
- `/api/v1/driver/orders/store` : POST method.
  - Body:
    ```
    {
        "id": "1",
        "comment": "Егор",
    }
    ```
- `/api/v1/driver/orders/appearance` : POST method.
  - Body:
    ```
    {
        "place_ids": "1" || [1, 2, 3],
        "appearance": 0 || 1,
    }
    ```
- `/api/v1/driver/clients/confirm-status` : POST method.
  - Body:
    ```
    {
        "id": "1" - client id,
        "files" : [ Image ]
    }
    ```
- `/api/v1/driver/clients/cancel-status` : POST method.
  - Body:
    ```
    {
        "order_id": "1"
    }
    ```

- `/api/v1/driver/clients/statuses` : POST method.
  - Result
    ```
    {
        "result": "success",
        "statuses": [
            {"id": 1, "name": "Пенсионер"},
        ]
    }
    ```