# Organization

### Entities

- Product
    - Id: Int
    - Image: String
    - Name: String
    - Price: Double
    - Description (Markdown): String
    - Command: String
    - Create date: Date
    - Update date: Date
    
- Purchased Product
    - Id: Int
    - Product: Product
    - Amount: Int
    
- Payment
    - Id: Int
    - User name: String
    - Products: Purchased Product[]
    - Delivered: Boolean
    - Payment type: String
    - Payment response: Boolean
    - Payment raw response: String
    - Create date: Date
    - Update date: Date

- Post
    - Id: Int
    - Name: String
    - Description (Markdown): String
    - Create date: Date
    - Update date: Date

- User
    - Id: Int
    - Email: String
    - Name: String
    - Password: String
    - Role: String
    - Create date: Date
    - Update date: Date
    
### Rests

- Product rest
    - Update product
        - Admin
        
    - Delete product
        - Admin
        
- Post rest
    - Update post
        - Admin
        
    - Delete post
        - Admin
        
- User rest
    - Show
        - Email Field
            - Admin
        - Everybody

    - Delete user
        - Admin
        - Its self
    
    - Update user
        - Admin
        - Its self

- Payment rest
    - Delete payment
        - Nobody
    
    - Update payment
        - Nobody
    
    - Store payment
        - Nobody
    
    - Show
        - Admin
     
    - IPN(`/check`): POST & PUT
        - When requested then should update payment state according to request body
            - When payment response is true then should create Delivered Product with delivered=false
            
    - Create Payment(`/pay`): POST
        - When requested then should create payment and response payment link 

- Auth rest
    - Login(`/auth/login`): POST
        - When requested then should authenticate then should response a jwt token
        
    - Register(`/auth/register`): POST
        - When requested then should register user in db(link to `POST users`) 
        
    - User(`/user`): GET
        - When requested then should validate client's jwt token and response user 


### Pages

- / : Posts

- /products: All products
    - When clicked in a product then should redirect to /products/{product} page
    - When clicked in a product to add to cart then should add to cart then should update in navigation bar
    
- /products/{product}: Show product info
    - When clicked to add to cart then should add to cart then should update in navigation bar

- /cart: Show cart
    - When clicked to finish payment and the nick name and payment type inputs are selected then should request 
to api `/payments/pay` and redirect to the response link that is the payment link

- /dash/login: Login to the system

- /dash/register: Register to the system

- /dash: User administration
    - Change password
    - Change email
    - Change name
    - Save changes

- /dash/users: Show all site's users
    - Open a card to edit user when click in the user
        - Change user data
        - Delete user
        - Save changes 
        - *If payment do not exist create once instead edit*
    - Show create button(will open the card)
    
- /dash/payments: Show all site's payments
    - Open card to show complete payment info
    
- /dash/products: Show all site's products
    - Show all products
    - Send to /dash/product/{product} page when click in a product
    - Show create button(will product page)

- /dash/products/{product}: Show product info
    - Change product data
    - Delete product
    - Save changes
    - *If product do not exist create once instead edit*

- /dash/posts: Show all posts
    - Show all products
    - Send to /dash/posts/{post} page when click in a post
    - Show create button(will post page)

- /dash/posts/{post}: Show post info
    - Change post data
    - Delete post
    - Save changes
    - *If post do not exist create once instead edit*
