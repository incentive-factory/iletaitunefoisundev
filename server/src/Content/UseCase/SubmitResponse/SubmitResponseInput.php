<?php

declare(strict_types=1);

namespace App\Content\UseCase\SubmitResponse;

use App\Content\Entity\Answer;
use App\Content\Entity\Format;
use App\Content\Entity\Quiz\Response;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class SubmitResponseInput
{
    /**
     * @var array<array-key, Answer>
     */
    public array $answers = [];

    public Response $response;

    #[Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        $getId = static fn (Answer $answer): ?int => $answer->getId();
        $responseAnswerIds = array_map($getId, $this->answers);
        $questionAnswerIds = $this->response->getQuestion()->getAnswers()->map($getId)->toArray();

        if (Format::Unique === $this->response->getQuestion()->getFormat() && count($this->answers) > 1) {
            $context->buildViolation('You may not give more than one answer to a single choice question. ')
                ->atPath('answers')
                ->addViolation();
        }

        if (count(array_diff($responseAnswerIds, $questionAnswerIds)) > 0) {
            $context->buildViolation('Some answers are not suggested by the question.')
                ->atPath('answers')
                ->addViolation();
        }
    }
}
