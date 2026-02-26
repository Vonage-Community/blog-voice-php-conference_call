# Build a Conference Call with PHP and Vonage

A sample PHP application that uses the [Vonage Voice API](https://developer.vonage.com/en/voice/voice-api/overview) to set up a conference call. Distribute a single Vonage virtual number and every caller who dials it is greeted with a text-to-speech message and dropped into the same conference room — ideal for impromptu team meetings or any multi-participant phone call.

## How It Works

When a caller dials your Vonage virtual number, Vonage sends a webhook to `answer.php`, which responds with a [Call Control Object (NCCO)](https://developer.vonage.com/en/voice/voice-api/ncco-reference) that plays a greeting and adds the caller to a named conference. Call lifecycle events (answered, completed, failed, etc.) are forwarded to `event.php` and written to the PHP error log.

## Prerequisites

- **PHP 8.3+**
- A [Vonage API account](https://developer.vonage.com/en/sign-up) with:
  - A purchased Vonage virtual number (voice-capable)
  - A Vonage Voice Application configured with the webhook URLs below
- [ngrok](https://ngrok.com/) (or any public HTTPS tunnel) for local development

## Project Structure

```
public/
  answer.php   # Returns the NCCO that greets callers and adds them to the conference
  event.php    # Receives and logs call event webhooks from Vonage
```

## Running Locally

### 1. Start the PHP development server

```bash
cd public/
php -S localhost:8080
```

Verify the NCCO is returned correctly:

```bash
curl http://localhost:8080/answer.php
```

You should see a JSON response containing a `talk` action followed by a `conversation` action.

### 2. Expose your local server with ngrok

Vonage needs to reach your application over the public internet. In a separate terminal:

```bash
ngrok http 8080
```

Copy the `https://` forwarding URL from the ngrok output — you will use it in the next step.

### 3. Create a Vonage Application

In the [Vonage Dashboard](https://dashboard.vonage.com/applications), create a new application, enable Voice capabilities, and set:

| Webhook | Value |
|---|---|
| **Answer URL** | `https://<your-ngrok-subdomain>.ngrok-free.app/answer.php` |
| **Event URL** | `https://<your-ngrok-subdomain>.ngrok-free.app/event.php` |

Download and save the generated `private.key` file into your project directory (you won't need it for this basic example, but it is required if you later make outbound API calls).

### 4. Link your Vonage number

On the application detail page, scroll to the **Numbers** section and click **Link** next to the virtual number you want to use.

### 5. Make a call

Dial your Vonage virtual number. You should hear:

> *"Thank you for joining the call today. You will now be added to the conference."*

Invite others to call the same number — everyone joins the same conference room. Incoming call events will appear in the terminal running the PHP web server.

## Configuration

The conference room name is set in `public/answer.php`:

```php
"name" => "weekly-team-meeting"
```

Change this string to create a differently named (or multiple) conference rooms.

## Going Further

- Add [call recording](https://developer.vonage.com/en/voice/voice-api/ncco-reference#record) via an NCCO `record` action
- Play hold music with a `stream` action while waiting for participants
- Assign a moderator using the `conversation` action's `moderator` option
- Replace the static NCCO with dynamic logic (e.g. database-driven room names per caller)

See the full [NCCO reference](https://developer.vonage.com/en/voice/voice-api/ncco-reference) and [Vonage Voice API code snippets](https://developer.vonage.com/en/voice/voice-api/code-snippets/before-you-begin) for more ideas.
