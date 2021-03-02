# Models

* Create
* Read
* Update
* Delete


## Usuario

* Create
	* `save`: argumentos `user_id` (se tiver valor vai executar `UPDATE`, senão `INSERT`)
* Read
	* `getInfo`: Filtrada pelo `user_id`


## Conta    

* Create
	* `save`: argumentos `balance`, `user_id` ou `account_id` (diferença entre executar `UPDATE` ou `INSERT`)
* Read
	* `getInfo`: Filtrada pelo `account_id`
	* `getList`: Filtrada pela `user_id`

## Transaction

* Create
	* `save`: argumentos `transation_type_id`, `value`
* Read
	* `getInfo`: Filtrada pelo `transaction_id`
	* `getList`: Filtrada pela `account_id`

---

# Controllers

## UserController

* login
    * argumentos: 
        * `API_TOKEN`: vem no header // caso vazio ou invalido retornar 403 (forbiden) 
        * `email`: body
        * `password`: body que deve vir criptografado do front MD5]
    * resutado: 
        * objeto tipo `userModel` (JSON)
        * `SESSION_ID` 
* crateUser
    * argumentos:
        * `API_TOKEN`: vem no header // caso vazio ou invalido retornar 403 (forbiden)
        * objeto tipo `userModel` (JSON)
    * resultado:
        * `user_id` que foi criado
* emailUpdate
    * argumentos: 
        * `API_TOKEN`: vem no header // caso vazio ou invalido retornar 403 (forbiden)
        * `SESSION_ID`: vem no header ou cookie // caso vazio ou invalido retornar 403 (forbiden)
        * `email`: body
        * `user_id`: body
    * resultado:
        * `BOOLEAN`: Indicativo de sucesso ou falha
* passwordUpdate
    * argumentos: 
        * `API_TOKEN`: vem no header // caso vazio ou invalido retornar 403 (forbiden)
        * `SESSION_ID`: vem no header ou cookie // caso vazio ou invalido retornar 403 (forbiden)
        * `password`: body = criptografado em MD5
        * `user_id`: body
    * resultado:
        * `BOOLEAN`: Indicativo de sucesso ou falha
* getAddress
* getAddressList
* saveAddress
* getLogs
    
## TransactionController

* getTransactionList
* deposit
* withdraw
* transfer
* getLogs

