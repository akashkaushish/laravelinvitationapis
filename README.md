Follow the below steps to setup the script

Step 1: Download the Code

Step 2: Run Command 'composer update' (It would add all the dependencies)

Step 3: open the .env file add the mysql database credentials, I made a databse on localhost name loudly

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=loudly
DB_USERNAME=root
DB_PASSWORD=root

Step 4: Run Command 'php artisan migrate', it would add the tables in database from migration files.

Step 5: If you want to test the APIs mannually using Postman, then follow:

Send Invitation API:  

Method: POST,
URL: http://127.0.0.1:8000/api/send
Parameters: sender, invited (Both are required and  should have int values (IDs of the Users Table), If these do not exist in User Table it would throw error. Only Valid entries would be accepted)

Respond Invitation API: Invited user can accept/decline to any received invitation

Method: PUT,
URL: http://127.0.0.1:8000/api/respond
Parameters: 
    invitation_id (Required, The ID of the invitations table, would check if inviation is there and its status is 'sent' otherwise gives error) 
    invited (Required and  should have int values (ID of the Invited user in users Table), If these do not exist in User Table it would throw error. Also                  check if the invited user is same as the user id sent in invited parameter.Only Valid entries would be accepted)
    status (Required field, value should be accept/decline, do not take any other value.)
    
Cancel Invitation API: Sender user can cancel to any sent invitation, only if its not responded by Invited user yet.

Method: PUT,
URL: http://127.0.0.1:8000/api/decline
Parameters: 
    invitation_id (Required, The ID of the invitations table, would check if inviation is there and its status is 'sent' otherwise gives error) 
    sender (Required and  should have int values (ID of the Sender user in users Table), If these do not exist in User Table it would throw error. Also check               if this is the same user who sent this invitation or not. Only Valid entries would be accepted)
    
    

Step 5: To test the invitation send API using PHPUnit

Run Command 'php artisan test --filter InvitationControllerTest'

It would automativally create 2 Users (Sender & Invited) and send an invitation.
