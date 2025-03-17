import sys
import dns.resolver
import json

def check_email_domain(email):
    domain = email.split('@')[-1]
    
    # Check if domain has MX records
    try:
        mx_records = dns.resolver.resolve(domain, 'MX')
        if mx_records:
            return {"valid_email": True, "message": "✅ Email Domain is Valid!"}
    except Exception:
        return {"valid_email": False, "message": "❌ Invalid Email Domain!"}

    return {"valid_email": False, "message": "❌ Invalid Email Domain!"}

if len(sys.argv) < 2:
    print(json.dumps({"status": "Error", "message": "No email provided."}))
    sys.exit(1)

email_address = sys.argv[1]
result = check_email_domain(email_address)
print(json.dumps(result, indent=4))
