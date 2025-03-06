# CSV Import Guide for Leads

## Recommended Headers

Your CSV file should include headers that match or closely resemble these database fields:

| CSV Header           | Database Field     | Description                                     |
|----------------------|--------------------|-------------------------------------------------|
| first_name           | first_name         | First name of the lead                          |
| last_name            | last_name          | Last name of the lead                           |
| email                | email              | Email address                                   |
| phone                | phone              | Primary phone number                            |
| mobile               | mobile             | Mobile/secondary phone number                   |
| status               | status             | Lead status (new, contacted, qualified, etc.)   |
| lead_status          | lead_status        | Additional status details                       |
| source               | source             | Lead source (website, referral, etc.)           |
| lead_source          | lead_source        | More specific source information                |
| budget               | budget             | Lead's budget                                   |
| property_interest    | property_interest  | ID of property the lead is interested in        |
| notes                | notes              | General notes about the lead                    |
| description          | description        | Detailed description                            |
| lead_class           | lead_class         | Classification (A, B, C)                        |
| type_of_request      | type_of_request    | Type of inquiry (information, viewing, etc.)    |
| last_follow_up       | last_follow_up     | Date of last follow-up (YYYY-MM-DD format)      |
| agent_follow_up      | agent_follow_up    | Requires follow-up (1 for yes, 0 for no)        |

## Sample CSV Format

