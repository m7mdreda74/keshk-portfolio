<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Contact Message</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
    .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .header { background: linear-gradient(135deg, #9063ff, #c084fc); padding: 30px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; letter-spacing: 0.5px; }
    .body { padding: 30px; }
    .field { margin-bottom: 20px; }
    .field label { display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #9063ff; font-weight: 700; margin-bottom: 6px; }
    .field p { margin: 0; font-size: 15px; color: #333; line-height: 1.6; background: #f9f7ff; border-left: 3px solid #9063ff; padding: 10px 14px; border-radius: 4px; }
    .footer { background: #f4f4f4; padding: 16px 30px; text-align: center; font-size: 12px; color: #999; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <h1>📩 New Portfolio Message</h1>
    </div>
    <div class="body">
      <div class="field">
        <label>From</label>
        <p>{{ $senderName }} &lt;{{ $senderEmail }}&gt;</p>
      </div>
      <div class="field">
        <label>Subject</label>
        <p>{{ $mailSubject }}</p>
      </div>
      <div class="field">
        <label>Message</label>
        <p>{{ $body }}</p>
      </div>
    </div>
    <div class="footer">
      Sent from your portfolio contact form · <a href="mailto:{{ $senderEmail }}" style="color:#9063ff;">Reply to {{ $senderName }}</a>
    </div>
  </div>
</body>
</html>
