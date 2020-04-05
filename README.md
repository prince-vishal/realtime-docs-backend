# ***PostMan Docs***

## About the App
Build a Presence service, similar to that of google docs, which shows people that are
currently viewing a particular doc, history of all the past viewers of a doc.
 This app has the following basic features:
- A basic user registration and authentication system, with JWT support for API.
- A page that contains the UI element/component (similar to the one shown
  towards the left in the image above) to show the people who are currently viewing the
  page. This page can only be accessed by registered users and authorized users.
- **UI** has the following features:
     1. You are able to see the registered name, email, avatar, etc on hovering over the
     avatar of current viewers.
     2. You are also able to see who all have visited the page in the past and the
     last timestamp they visited the page.
     3. You will see an error page when your are authenticated but not authorized to view the doc you are trying to acess.
     4. **Only certain authorized users can view a doc**.
     5. **You can share a doc using persons email id**.
     5. **You can create a doc**.

### Models
- **User** : This is used to interact with the users table, and contains logic
 for fetching relationships like "viewed docs","owned docs". 
- **Doc** : This is used to interact with the docs table, and contains 
logic for fetching relationships like "viewers","owner".
- **DocViewer** : This is used to interact with the doc_viewers table, 
and contains logic for maintaining viewers of a doc. "doc_viewers" is a pivot
 table to maintain a Many to Many relationship between Doc and User(Viewer). 
- **DocUser** : This is used to interact with the doc_users table, 
and contains logic for maintaining users with which a Doc is shared with,along
 with the access role assigned i.e edit or view. "doc_users" is a pivot
 table to maintain a Many to Many relationship between Doc and User (Shared to). 
  
### **Deployment Details** 
- #####Backend :
    1. **Database** : Amazon RDS instance
    2. **Server** : Heroku
- ##### Frontend:
    1. Hosted on Github pages

### **API Documentation** :
> [**Postman Docs API**](https://documenter.getpostman.com/view/6037135/SzYbyxVX?version=latest)

### App Link
>[**Postman Docs**](https://theprincevishal.in/realtime-docs)

 


#### Author
[**Prince Sinha**](https://theprincevishal.in)
