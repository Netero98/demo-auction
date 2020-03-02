<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Service;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Service\JoinConfirmationSender;
use App\Frontend\FrontendUrlGenerator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;

/**
 * @covers JoinConfirmationSender
 */
class JoinConfirmationSenderTest extends TestCase
{
    public function testSuccess(): void
    {
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());
        $confirmUrl = 'http://test/join/confirm?token=' . $token->getValue();

        $frontend = $this->createMock(FrontendUrlGenerator::class);
        $frontend->expects($this->once())->method('generate')->with(
            $this->equalTo('join/confirm'),
            $this->equalTo(['token' => $token->getValue()]),
        )->willReturn($confirmUrl);

        $mailer = $this->createMock(Swift_Mailer::class);
        $mailer->expects($this->once())->method('send')
            ->willReturnCallback(static function (Swift_Message $message) use ($to, $confirmUrl): int {
                self::assertEquals([$to->getValue() => null], $message->getTo());
                self::assertEquals('Join Confirmation', $message->getSubject());
                self::assertStringContainsString($confirmUrl, $message->getBody());
                return 1;
            });

        $sender = new JoinConfirmationSender($mailer, $frontend);

        $sender->send($to, $token);
    }

    public function testError(): void
    {
        $to = new Email('user@app.test');
        $token = new Token(Uuid::uuid4()->toString(), new DateTimeImmutable());

        $frontend = $this->createStub(FrontendUrlGenerator::class);
        $frontend->method('generate')->willReturn('http://test/join/confirm?token=' . $token->getValue());

        $mailer = $this->createStub(Swift_Mailer::class);
        $mailer->method('send')->willReturn(0);

        $sender = new JoinConfirmationSender($mailer, $frontend);

        $this->expectException(RuntimeException::class);
        $sender->send($to, $token);
    }
}