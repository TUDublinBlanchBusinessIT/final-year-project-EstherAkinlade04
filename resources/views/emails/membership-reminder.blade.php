<!DOCTYPE html>
<html>
<body style="font-family:Arial;padding:20px;background:#f3f0ff;">
    <h2 style="color:#6d28d9;">Vault Fitness</h2>

    <p>Hi {{ $user->name }},</p>

    <p>Your membership will expire in 3 days.</p>

    <p>
        Renew now to continue booking classes and accessing premium sessions.
    </p>

    <a href="{{ url('/checkout') }}"
       style="background:#6d28d9;color:white;padding:10px 18px;border-radius:8px;text-decoration:none;">
        Renew Membership
    </a>

    <p style="margin-top:20px;">Stay strong 💎</p>
</body>
</html>