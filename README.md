This repo is a starter for a simple saas that only accept one off payment s with stripe.

- Create stripe account
- Get stripe keys, webhook secret and add to env (webhook endpoint is "stripe/webook')
- Create product in stripe
- create price from product
- copy price_id and add to env under PRICE_ID

## TODO 
- [ ] Sync user data to stripe when user model is updated
- [ ] Listen for product changes and update database
 
