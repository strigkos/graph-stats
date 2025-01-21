<?php
/**
 * Tests for timeline date object.
 *
 * @package GravityKit\GravityCharts
 */

namespace Timeline;

use Exception;
use GravityKit\GravityCharts\Timeline\TimelineDate;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for {@see TimelineDate}
 *
 * @since 1.6
 */
final class TimelineDateTest extends TestCase {

	/**
	 * Test cases for {@see TimelineDate::with_scale()} with an invalid scale.
	 *
	 * @since 1.6
	 */
	public function testScaleGuard(): void {
		$this->expectExceptionMessage( 'Provided scale "invalid" is invalid. Can only be one of: "day", "week", "month", "quarter", "year".' );

		( new TimelineDate( '2023-07-01' ) )->with_scale( 'invalid' );
	}

	/**
	 * Data provider for unit tests.
	 *
	 * @since 1.6
	 * @return array[]
	 */
	public function scalesDataProvider(): array {
		return [
			'day'       => [ '14-02-1988', 'day', '14-02-1988' ],
			'week'      => [ '13-07-2023', 'week', '10-07-2023' ],
			'month'     => [ '13-07-2023', 'month', '01-07-2023' ],
			'quarter 1' => [ '27-02-2023', 'quarter', '01-01-2023' ],
			'quarter 2' => [ '13-06-2023', 'quarter', '01-04-2023' ],
			'quarter 3' => [ '17-09-2023', 'quarter', '01-07-2023' ],
			'quarter 4' => [ '04-11-2023', 'quarter', '01-10-2023' ],
			'year'      => [ '13-07-2023', 'year', '01-01-2023' ],
		];
	}

	/**
	 * Test cases for {@see TimelineDate::with_scale()}.
	 *
	 * @param string $date          The Date.
	 * @param string $scale         The scale.
	 * @param string $expected_date The expected date.
	 *
	 * @dataProvider scalesDataProvider The data provider.
	 * @return void
	 * @throws \Exception If the date could not be created.
	 */
	public function testScale( string $date, string $scale, string $expected_date ): void {
		$date     = new TimelineDate( $date );
		$new_date = $date->with_scale( $scale );
		self::assertSame( $expected_date, $new_date->format( 'd-m-Y' ) );
		self::assertNotSame( $date, $new_date );
	}


	/**
	 * Data provider for unit tests.
	 *
	 * @since 1.6
	 * @return array[]
	 */
	public function formatDataProvider(): array {
		return [
			'normal'    => [ '14-02-1988', 'Y-m-d', '1988-02-14' ],
			'quarter 1' => [ '27-02-2023', 'Y \Qq', '2023 Q1' ],
			'quarter 2' => [ '13-06-2023', 'Y \Qq', '2023 Q2' ],
			'quarter 3' => [ '17-09-2023', 'Y \Qq', '2023 Q3' ],
			'quarter 4' => [ '04-11-2023', 'Y \Qq', '2023 Q4' ],
			'quarter q' => [ '04-11-2023', 'Y \Q\q', '2023 Qq' ],
		];
	}

	/**
	 * Test cases for {@see TimelineDate::format()}.
	 *
	 * @param string $date            The Date.
	 * @param string $format          The format.
	 * @param string $expected_result The expected result.
	 *
	 * @dataProvider formatDataProvider The data provider.
	 * @return void
	 * @throws \Exception If the date could not be created.
	 */
	public function testFormat( string $date, string $format, string $expected_result ): void {
		self::assertSame( $expected_result, ( new TimelineDate( $date ) )->format( $format ) );
	}

	/**
	 * Data provider for timeline timezone tests.
	 *
	 * @since 1.7.5
	 *
	 * @return array[]
	 */
	public function timezoneTestProvider(): array {
		return [
			'null'             => [ '2023-09-15 23:55:43', '2023-09-16 01:55:43', 'Europe/Amsterdam', null ],
			'Europe/Amsterdam' => [
				'2023-09-15 23:55:43',
				'2023-09-15 23:55:43',
				'Europe/Amsterdam',
				'Europe/Amsterdam',
			],
			'GMT-1 to +1'      => [ '2023-09-15 23:55:43', '2023-09-16 01:55:43', 'GMT+1', 'GMT-1' ],
		];
	}

	/**
	 * Test case for {@see TimelineDate::__construct()} with a specific timezone.
	 *
	 * @since        1.7.2
	 *
	 * @dataProvider timezoneTestProvider The data provider.
	 *
	 * @param string      $input              The input date.
	 * @param string      $expected           The expected output date.
	 * @param string      $time_zone          The timezone for formatting.
	 * @param string|null $original_time_zone The timezone the input is in.
	 *
	 * @throws Exception If the dates could not be created.
	 */
	public function testTimezone( string $input, string $expected, string $time_zone, ?string $original_time_zone ): void {
		$date = new TimelineDate(
			$input,
			new \DateTimeZone( $time_zone ),
			$original_time_zone ? new \DateTimeZone( $original_time_zone ) : null
		);

		self::assertSame( $expected, $date->format( 'Y-m-d H:i:s' ) );
	}
}
