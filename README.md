<h2>Php Pdo Class For Crud System</h2>

Construct function :

1. establishConnection
2. createDatabase
3. useDatabase

#### Crud System

Functions

1. createTable
    - This funciton takes two parameters : First one is for table name and second one is for tables columns according to their datatype.
    - You can choose 'string' for datatype VARCHAR(255) NOT NULL
    - Ethier you can select 'int' for datatype INT(11) NOT NULL
    - If you want custom datatype then you need to specify it according to your own need with syntax like :
        - 'column name' => 'your custom datatype'
2. insertRecord
    - This function takes three parameters: First for table name
    -Second for your table data that you want to insert in table you can simply pass $_POST
    -The last parameter is for redirection if you want to redirect any other page.

3. showRecord
    - This function also takes three parameter:
    -First for tablename
    -Second for choosing columns
    -Last one parameter is for add condition to your record that you want to read.
    
4. deleteRecord
    - This function is for delete record from your table first it takes table name second is for your condition and third one if for redirection if you want.
5. updateRecord
    - This funciton takes three parameters : First one is for table name and second one is for record that you want to update it should be an array.
    - Third is for condition to update a specific record.
    - Last one is for redirection if you want.

Thats All In This Class I Hope It Can Help To Save Your Time And To Speed Up Your Work :)
